const { execSync } = require('child_process')
const pkg = require('../package.json')

const date = new Date().toISOString().slice(0,10).replace(/-/g, '')
const count = execSync('git rev-list --count HEAD').toString().trim()

console.log(`v${pkg.version}-build-${date}-${count}`)
