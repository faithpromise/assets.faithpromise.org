module.exports = function (grunt) {

    // Load all tasks
    require('load-grunt-tasks')(grunt);

    // Show elapsed time
    require('time-grunt')(grunt);

    // Roots
    var release_root = '_release';

    // Project configuration.
    grunt.initConfig(
        {
            clean: {
                release: [release_root + '/*', '!' + release_root + '/.gitkeep']
            },
            copy: {
                release_files: {
                    expand: true,
                    src: [
                        './**/*.*',
                        './artisan',
                        '!./_release/**/*.*',
                        '!./node_modules/**/*.*',
                        '!./public/docs/**/*.*',
                        '!./public/images/**/*.*',
                        '!./public/svg/**/*.*',
                        '!./storage/**/*.*',
                        '!./vendor/**/*.*',
                        '!./.env',
                        '!./.gitattributes',
                        '!./.gitignore',
                        '!./Gruntfile.js',
                        '!./package.json',
                        '!./readme.md',
                        '!./TODO.txt'
                    ],
                    dest: release_root
                }
            },
            git_deploy: {
                production: {
                    options: {
                        url: 'git@github.faithpromise.org:faithpromise/assets.faithpromise.org.git',
                        branch: 'release'
                    },
                    src: release_root
                }
            }
        }
    );

    // Register tasks
    grunt.registerTask('default', []);

    grunt.registerTask('deploy_production', [
        'clean:release',
        'copy:release_files',
        'git_deploy:production'
    ]);

};