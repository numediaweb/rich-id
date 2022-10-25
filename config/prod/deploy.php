<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class() extends DefaultDeployer {
    /**
     * @return \EasyCorp\Bundle\EasyDeployBundle\Configuration\DefaultConfiguration
     */
    public function configure()
    {
        return $this->getConfigBuilder()
            // Enable this to debug omposer issues
//                    ->composerInstallFlags('--prefer-dist --no-interaction --no-dev')
            // ->setDefaultConfiguration(\EasyCorp\Bundle\EasyDeployBundle\Configuration\DefaultConfiguration::SYMFONY_3)
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
                    ->server('rich.almois.me')
            // the absolute path of the remote server directory where the project is deployed
                    ->deployDir('/var/www/almoisme-rich/')
            // the URL of the Git repository where the project code is hosted
                    ->repositoryUrl('git@github.com:numediaweb/rich-id.git')
            // the repository branch to deploy
                    ->repositoryBranch('main')
                    ->keepReleases(3)
            // Files and directories to be shared between all releases
                    ->sharedFilesAndDirs(
                [
                    'var/log/',
                ]
            );
    }

    /**
     * Executed just before changing the symlink to the new release.
     */
    public function beforePublishing()
    {
        $this->runRemote('composer dump-env prod');
        $this->runRemote('{{ console_bin }} doctrine:migrations:migrate --no-interaction --quiet --allow-no-migration');
        $this->runRemote('npm install');
        $this->runRemote('npm run build');
    }

    /**
     * Sync the env file.
     */
    public function beforeStartingDeploy()
    {
        // Envvars
        $this->runLocal('scp .env.prod rich.almois.me:/var/www/almoisme-rich/.env.prod');
    }

    /**
     * Executed just before doing the composer install, setting the permissions, installing assets, etc.
     */
    public function beforePreparing()
    {
        $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }}/.env');
        $this->runRemote('cp {{ deploy_dir }}/.env.prod {{ project_dir }}/.env.prod');
    }
};
