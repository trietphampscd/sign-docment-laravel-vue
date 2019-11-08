// module.exports = {
//   baseUrl: './',
//   lintOnSave: false,
//   runtimeCompiler: true,
//   devServer: {
//     https: true,
//     // proxy: 'https://localhost:443',
//   }
// }

const path = require('path')

const host = 'localhost'
const port = 8080

module.exports = {
  lintOnSave: false,
  baseUrl: `https://${host}:${port}/`,

  devServer: {
    https: true,
    port,
    host,
    hotOnly: true,
    // disableHostCheck: true,
    clientLogLevel: 'warning',
    inline: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
      'Access-Control-Allow-Headers': 'Origin, X-Requested-With, Content-Type, Accept, Authorization'
    }
  }
}