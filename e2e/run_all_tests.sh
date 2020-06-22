function run_test() {
	python3.8 rungame.py test --host $1 -i tests/$2 -o tests/$2.canondata
}

run_test $1 game1
run_test $1 game2
run_test $1 promotion
run_test $1 scholarscheckmate
