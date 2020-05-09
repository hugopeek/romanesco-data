id: 149
name: returnFirstHit
description: 'Feed it a bunch of properties and it spits out the first one that''s not empty. Property names are irrelevant. Sort order is all that matters.'
category: f_basic
snippet: "/**\n * returnFirstHit snippet\n *\n * Feed it a bunch of properties and it spits out the first one that's not empty.\n * Property names are irrelevant. Sort order is all that matters.\n *\n * [[!returnFirstHit?\n *     &1=`[[+redirect_id]]`\n *     &2=`[[+next_step]]`\n *     &3=`[[*fb_redirect_dynamic]]`\n *     &4=`[[*fb_redirect_id]]`\n *     &default=`Nothing there!`\n * ]]\n */\n\nforeach ($scriptProperties as $property) {\n    if ($property) return $property;\n}\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * returnFirstHit snippet\n *\n * Feed it a bunch of properties and it spits out the first one that's not empty.\n * Property names are irrelevant. Sort order is all that matters.\n *\n * [[!returnFirstHit?\n *     &1=`[[+redirect_id]]`\n *     &2=`[[+next_step]]`\n *     &3=`[[*fb_redirect_dynamic]]`\n *     &4=`[[*fb_redirect_id]]`\n *     &default=`Nothing there!`\n * ]]\n */\n\nforeach ($scriptProperties as $property) {\n    if ($property) return $property;\n}\nreturn '';"

-----


/**
 * returnFirstHit snippet
 *
 * Feed it a bunch of properties and it spits out the first one that's not empty.
 * Property names are irrelevant. Sort order is all that matters.
 *
 * [[!returnFirstHit?
 *     &1=`[[+redirect_id]]`
 *     &2=`[[+next_step]]`
 *     &3=`[[*fb_redirect_dynamic]]`
 *     &4=`[[*fb_redirect_id]]`
 *     &default=`Nothing there!`
 * ]]
 */

foreach ($scriptProperties as $property) {
    if ($property) return $property;
}
return '';