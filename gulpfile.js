// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('js/source/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Sass
/*
gulp.task('sass', function() {
    return gulp.src('sass/*.scss')
        .pipe(sass({includePaths: ['./sass']}))
        .pipe(sass())
        .pipe(gulp.dest('stylesheets'));
});
*/

// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src(['js-base/source/*.js', 'js/source/*.js'])
        .pipe(concat('jjamerson_lb.js'))
        .pipe(gulp.dest('js'))
        .pipe(rename('jjamerson_lb.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch(['js-base/source/*.js', 'js/source/*.js'], ['lint', 'scripts']);
    //gulp.watch('sass/*.scss', ['sass']);
});

// Default Task
gulp.task('default', ['lint', /* 'sass', */ 'scripts', 'watch']);