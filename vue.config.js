module.exports = {
	productionSourceMap: false,
    configureWebpack: {
        externals: {
            vue: 'Vue',
            'vue-router': 'VueRouter',
            axios: 'axios'
        },
    },
	devServer: {
		proxy: {
			"/messager": {
				target: "http://messager.git.suconghou.cn:8070",
				changeOrigin: true
			}
		}
	}
};
