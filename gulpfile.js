var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var plumber = require('gulp-plumber');
var shell = require('gulp-shell');
var phpcs = require('gulp-phpcs');
var phpcbf = require('gulp-phpcbf');
var gutil = require('gutil');

gulp.task('dev::tests', function() {
    var options = {
        testSuite:'MsgPackPhp'
    };
    gulp.src('phpunit.xml')
        .pipe(plumber())
        .pipe(phpunit('vendor/bin/phpunit', options));
});
gulp.task('dev::composer', shell.task([
    'composer install --ignore-platform-reqs --optimize-autoloader'
]));
gulp.task('dev::watchers', function() {
    gulp.watch(
        [
            'phpunit.xml',
            'src/**/*.php',
            'src/**/**/*.php',
            'src/**/**/**/*.php'
        ],
        ['dev::tests']
    );
    // gulp.watch(
    //     [
    //         'phpunit.xml',
    //         'src/**/*.php',
    //         'src/**/**/*.php',
    //         'src/**/**/**/*.php'
    //     ],
    //     ['dev::linters']
    // );
    gulp.watch('composer.lock', ['dev::composer']);
});

gulp.task('dev::linters', function () {
    return gulp.src(['src/**/*.php',
        'src/**/**/*.php',
        'src/**/**/**/*.php',
        '!src/vendor/**/*.*'])
        .pipe(phpcs({
            bin: 'vendor/bin/phpcs',
            standard: 'PSR2',
            warningSeverity: 0,
            exclude: "*Test.php"
        }))
        // Log all problems that was found
        .pipe(phpcs.reporter('log'));
});
gulp.task('phpcbf', function () {
    return gulp.src(['src/**/*.php',
        'src/**/**/*.php',
        'src/**/**/**/*.php',
        '!src/vendor/**/*.*'])
        .pipe(phpcbf({
            bin: 'vendor/bin/phpcbf',
            standard: 'PSR2',
            warningSeverity: 0
        }))
        .on('error', gutil.log)
        .pipe(gulp.dest('src'));
});
