killall soffice.bin
killall unoserver

cleanup() {
    killall unoserver
    exit
}
trap cleanup INT TERM

unoserver &

echo -e "Waiting for unoserver to start..."

while ! nc -z 127.0.0.1 2002; do
  echo -n "."
  sleep 0.1 # wait for 1/10 of the second before check again
done

echo -e "Running tests"
vendor/bin/phpunit

killall unoserver

exit $?
