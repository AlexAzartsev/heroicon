const fs = require('fs');
const path = require('path');

const iconsfolders = ['./resources/icons/outline', './resources/icons/solid'];
let files = [];
iconsfolders.forEach((folder) => {
  const folderFiles = fs.readdirSync(folder).map((fileName) => path.join(folder, fileName));
  files = [...folderFiles, ...files];
});
// eslint-disable-next-line no-shadow
const data = files.map((path) => {
  const type = path.split('/').reverse()[1];
  const name = path.split('/').reverse()[0].replace('.svg', '');
  const content = fs.readFileSync(path, 'utf-8');
  return { type, name, content };
});
fs.writeFile(
  './resources/js/icons.js',
  `export const icons = ${JSON.stringify(data)}`,
  (err) => {
    if (err) {
      // eslint-disable-next-line no-console
      console.error('Crap happens');
    }
  },
);
// eslint-disable-next-line no-console
console.log('\x1b[32m./resources/js/icons.js successfully generated.\x1b[0m');
