trap cleanup INT TERM

cleanup() {
    killall unoserver
}

unoserver --daemon
sleep 1

vendor/bin/phpunit
