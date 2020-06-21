release:
	git checkout master && yarn buildwc && mv dist/air-messager.min.js ../publish/air-messager.master.min.js && \
	git checkout classical && yarn buildwc && mv dist/air-messager.min.js ../publish/air-messager.classical.min.js && \
	git checkout github && yarn buildwc && mv dist/air-messager.min.js ../publish/air-messager.github.min.js && \
	git checkout view && yarn buildwc && mv dist/air-messager.min.js ../publish/air-messager.view.min.js && \
	cd publish && ls
