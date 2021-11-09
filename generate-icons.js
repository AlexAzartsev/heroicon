const fs = require('fs');
const path = require('path');

const iconsfolders = [
  { type: 'outline', path: './resources/icons/heroicons/outline' },
  { type: 'solid', path: './resources/icons/heroicons/solid' },
  { type: 'fa-brands', path: './resources/icons/fa/free/brands' },
  { type: 'fa-regular', path: './resources/icons/fa/free/regular' },
  { type: 'fa-solid', path: './resources/icons/fa/free/solid' },
];
let files = [];
iconsfolders.forEach((folder) => {
  const { type } = folder;
  const folderFiles = fs.readdirSync(folder.path).map((fileName) => ({
    type,
    path: path.join(folder.path, fileName),
  }));
  files = [...folderFiles, ...files];
});
// eslint-disable-next-line no-shadow
const data = files.map((file) => {
  const { type } = file;
  const name = file.path.split('/').reverse()[0].replace('.svg', '');
  const content = fs.readFileSync(file.path, 'utf-8').replace(/<!--(.*?)-->/gm, '');
  return { type, name, content };
});
fs.writeFile('./resources/js/icons.js', `export const icons = ${JSON.stringify(data)}`, (err) => {
  if (err) {
    // eslint-disable-next-line no-console
    console.error('Crap happens');
  }
});
// eslint-disable-next-line no-console
console.log('\x1b[32m./resources/js/icons.js successfully generated.\x1b[0m');
