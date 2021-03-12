const fs = require('fs');
const path = require('path');
const iconsfolders = ['./resources/icons/outline', './resources/icons/solid'];
let files = []
iconsfolders.forEach(function (folder) {
  let folderFiles = fs.readdirSync(folder).map(fileName => {
    return path.join(folder, fileName)
  })
  files = [...folderFiles, ...files]
})
let data = files.map(function (path) {
  let type = path.split('/').reverse()[1];
  let name = path.split('/').reverse()[0].replace('.svg', '');
  let content = fs.readFileSync(path, 'utf-8');
  return {type, name, content}
})
fs.writeFile('./resources/js/icons.js', 'export const icons = ' + JSON.stringify(data), function (err) {
  if (err) {
    console.error('Crap happens');
  }
})
console.log('\x1b[32m./resources/js/icons.js successfully generated.\x1b[0m')