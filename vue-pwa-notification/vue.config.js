const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  lintOnSave: false,
  transpileDependencies: true,
   pwa: {
    name: "Vue Firebase Laravel PWA",
    themeColor: "#ffffff",
    msTileColor: "#fff3e0",
    appleMobileWbeAppCapable: "yes",
    appleMobileWebAppStatusBarStyle: "#fff3e0",
    manifestOptions: {
      background_color: "#ffe24a",
    }
  }
})
