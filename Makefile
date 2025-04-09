start-client:
	cd client && symfony server:start --port=8000 --no-tls &

start-admin:
	cd admin && symfony server:start --port=8001 --no-tls &

start-api:
	cd api && symfony server:start --port=8002 --no-tls &


stop-port:
	@PORT=$(port) && \
	PID=$$(netstat -ano | grep ":$${PORT} " | grep LISTENING | awk '{print $$NF}' | head -n 1) && \
	if [ -n "$$PID" ]; then \
		echo "Killing process on port $$PORT (PID: $$PID)"; \
		taskkill //PID $$PID //F > /dev/null; \
	else \
		echo "No process found on port $$PORT"; \
	fi

stop-client:
	$(MAKE) port=8000 stop-port

stop-admin:
	$(MAKE) port=8001 stop-port

stop-api:
	$(MAKE) port=8002 stop-port

start-all: start-client start-admin start-api

stop-all: stop-client stop-admin stop-api
