
module.exports = function (grunt) {
    
    grunt.initConfig({
        concat: {
          dist: {
            src: ['../amd/src/*.js'],
            dest: '../amd/src/libs.min.js'
          }
        },
        uglify: {
            my_target: {
                files: {
                    '../amd/src/libs.min.js': ['../amd/src/libs.js']
                }
            }
        },
        watch: {
            scripts: {
              files: '../amd/src/*.js',
              tasks: ['build'],
              options: {
                interrupt: true
              }
            }
          },
        amdcheck: {
            dev: {
              options: {
                excepts: ['module'],
                exceptsPaths: ['require', /^jquery\./]
              },
              files: [
                {
                  expand: true,
                  cwd: '../amd/src/',
                  src: ['../amd/src/*.js'],
                  dest: '../amd/build/'
                }
              ]
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');    
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-amdcheck');
    
    grunt.registerTask('build', ['concat','uglify','amdcheck']);
};