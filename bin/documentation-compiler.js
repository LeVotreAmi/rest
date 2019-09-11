const fs = require('fs-extra');
const compileRefs = require('json-refs').resolveRefsAt;
const deepSortObject = require('deep-sort-object');
const loadYaml = require('js-yaml').load;
const converter = require('api-spec-converter');
const sprintf = require('sprintf-js').sprintf;

const FILE_PATH_PATTERN = `${__dirname}/../static/openapi/api/%s.json`;

function yamlRefs(filepath) {
  const options = {
    loaderOptions: {
      processContent: function processContent(content, callback) {
        callback(undefined, loadYaml(content.text));
      },
    },
  };

  return new Promise((resolve) => {
    compileRefs(filepath, options).then((results) => {
      resolve(results.resolved);
    }, (err) => {
      // eslint-disable-next-line no-console
      console.log(err);
      process.exit(1);
    });
  });
}

async function compileYamlRefs(filepath) {
  return yamlRefs(filepath);
}

async function sortObjectAlphabetically(object) {
  return deepSortObject(object);
}

async function transformObjectToJson(object) {
  return JSON.stringify(object, null, 2);
}

async function saveToFile(json, filename) {
  return fs.outputFile(
      sprintf(FILE_PATH_PATTERN, filename),
      json
  );
}

async function convertOas3ToSwagger2(filename) {
  const converted = await converter.convert({
      from: 'openapi_3',
      to: 'swagger_2',
      source: sprintf(FILE_PATH_PATTERN, filename),
  });

  return saveToFile(converted.stringify(), `${filename}_sw2`);
}

compileYamlRefs(`${__dirname}/../docs/api/User/OpenAPI.yaml`)
  .then(object => sortObjectAlphabetically(object))
  .then(object => transformObjectToJson(object))
  .then(json => saveToFile(json, 'user'))
  .then(() => convertOas3ToSwagger2('user'))
  .then(() => {
    // eslint-disable-next-line no-console
    console.info('User documentation compiled.');
  });
