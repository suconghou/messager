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
				target: "http://127.0.0.1:9988",
				changeOrigin: true
			}
		}
	}
};
