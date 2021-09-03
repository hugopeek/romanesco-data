<?php
require (dirname(__DIR__, 3) . '/_operations/vendor/autoload.php' );

use Symfony\Component\Yaml\Yaml;

function buildPatch($data_dir, &$patch)
{
    $files = array_diff(scandir($data_dir), array('..', '.'));
    foreach($files as $file)
    {
        $key = $data_dir.DIRECTORY_SEPARATOR.$file;
        if(is_dir($key))
        {
            buildPatch($key, $patch);
        }
        else
        {
            $patch[$key] = extractIds($key);
        }
    }
}

function extractIds($file)
{
    $fp = fopen($file, "r");
    $d = stream_get_line($fp, 104857600, "-----");
    fclose($fp);
    $yaml = Yaml::parse($d);
    $ids = array();
    if(isset($yaml['id']))
    {
        $ids['id'] = $yaml['id'];
    }
    if(isset($yaml['parent']))
    {
        $ids['parent'] = $yaml['parent'];
    }
    // Debug and verification
    if(empty($ids))
    {
        echo "W1: Neither ID, nor parent is set.\t'$file\n";
    }
    if(isset($ids['parent']) && ! isset($ids['id']))
    {
        echo "W2: Parent set, but not ID.       \t'$file\n";
    }
    if(isset($ids['id']) && ! is_int($ids['id']))
    {
        echo "W3: ID is not numeric.           \t'$file\n";
        unset($ids['id']);
    }
    if(isset($ids['parent']) && ! is_int($ids['parent']))
    {
        echo "W4: Parent is not numeric.       \t'$file\n";
        unset($ids['parent']);
    }
    return $ids;
}

function applyPatch($patch)
{
    foreach($patch as $file => $ids)
    {
        if(!empty($ids))
        {
            // Extract YAML header
            $fp = fopen($file, "rb");
            $d = stream_get_line($fp, 104857600, "-----");
            $yaml = Yaml::parse($d);
            // Extract other content, if existing
            $content = (!feof($fp)) ? "\n-----".fread($fp, filesize($file)) : "";
            fclose($fp);
            echo "$file\n";
            // Apply patch
            foreach($ids as $k => $v)
            {
                $yaml[$k] = $v;
            }
            // Write back full file
            $fp = fopen($file, "wb");
            fwrite($fp, Yaml::dump($yaml));
            fwrite($fp, $content);
            fclose($fp);
        }
    }
}

function run($argv)
{
    $cmd = isset($argv[1]) ? $argv[1] : null;
    $data_dir = isset($argv[2]) ? $argv[2] : null;
    $patch_file = isset($argv[3]) ? $argv[3] : null;
    if(!is_dir($data_dir) || empty($patch_file))
    {
        $cmd = ""; // Invalidate
    }
    switch($cmd)
    {
        case 'build':
            $patch = array();
            buildPatch($data_dir, $patch);
            file_put_contents($patch_file, json_encode($patch, JSON_PRETTY_PRINT));
            break;
        case 'apply':
            $patch = json_decode(file_get_contents($patch_file), true);
            applyPatch($patch);
            break;
        default:
            echo "Usage:\n\tphp id_patch build <data-dir> <patch-file>\n\tphp id_patch apply <data-dir> <patch-file>\n";
    }
}

run($argv);
