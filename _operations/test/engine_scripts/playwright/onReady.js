module.exports = async (page, scenario, viewport, isReference, browserContext) => {
  console.log('SCENARIO > ' + scenario.label);
  await require('./clickAndHoverHelper')(page, scenario);

  // add more ready handlers here...
  page.evaluate(async () => {
    $('.lazy').addClass('initial');
    lazyLoadInstance.loadAll();
  });
};
