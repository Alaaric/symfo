start-client:
	cd client && symfony server:start --port=8000 --no-tls &

start-admin:
	cd admin && symfony server:start --port=8001 --no-tls &

start-api:
	cd api && symfony server:start --port=8002 --no-tls &

start-worker-api:
    cd api && php bin/console messenger:consume async &

start-scheduler-api:
    cd api && php bin/console messenger:consume scheduler_email &


stop-port:
	@PORT=$(port) && \
	PID=$$(netstat -ano | grep ":$${PORT} " | grep LISTENING | awk '{print $$NF}' | head -n 1) && \
	if [ -n "$$PID" ]; then \
		echo "Killing process on port $$PORT (PID: $$PID)"; \
		taskkill //PID $$PID //F > /dev/null; \
	else \
		echo "No process found on port $$PORT"; \
	fi

stop-workers:
	cd api && php bin/console messenger:stop-workers

stop-client:
	$(MAKE) port=8000 stop-port

stop-admin:
	$(MAKE) port=8001 stop-port

stop-api:
	$(MAKE) port=8002 stop-port

start-all: start-client start-admin start-api start-worker-api start-scheduler-api

stop-all: stop-workers stop-client stop-admin stop-api 
