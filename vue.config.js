module.exports = {
	productionSourceMap: false,
	devServer: {
		proxy: {
			"/messager": {
				target: "http://messager.git.suconghou.cn:8070",
				changeOrigin: true
			}
		}
	}
};
