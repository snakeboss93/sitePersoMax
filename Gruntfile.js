module.exports = function (grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        cssmin: {
            combine: {
                options: {
                    report: 'min'
                },
                files: {
                    'css/min.css': [
                        'css/bootstrap.css',
                        'css/select2.css',
                        'css/override.css',
                        'css/font-awesome.css'
                    ]
                }
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMapName: 'js/built/app.map'
            },
            dist: {
                files: {
                    'js/min.js': [
                        'js/jquery.js',
                        'js/bootstrap.js',
                        'js/select2.js',
                        'js/init.js'
                    ]
                }
            }
        }
    });

    grunt.registerTask('default', ['css', 'javascript']);
    grunt.registerTask('css', ['cssmin']);
    grunt.registerTask('javascript', ['uglify']);

};