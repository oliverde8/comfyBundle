<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Command;

use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetConfigsCommand extends Command
{
    public const ARG_PATH = "path";
    public const OPT_SCOPE = "scope";

    /** @var ConfigManagerInterface */
    protected $configManager;

    public function __construct(ConfigManagerInterface $configManager)
    {
        parent::__construct();
        $this->configManager = $configManager;
    }


    protected function configure(): void
    {
        parent::configure();
        $this->setName("comfy:get");
        $this->setDescription("Get 'raw' configuration values for a given scope or default scope.");

        $this->addArgument(self::ARG_PATH, InputArgument::REQUIRED);
        $this->addOption(self::OPT_SCOPE, null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument(self::ARG_PATH);
        $scope = $input->getOption(self::OPT_SCOPE) ?? null;

        $sfs = new SymfonyStyle($input, $output);
        $configs = $this->configManager->getAllConfigs()->get($path);

        if (empty($configs)) {
            $sfs->error("No configs found for path '$path'");
            return self::SUCCESS;
        }

        if (!is_array($configs)) {
            $configs = [$configs];
        }

        $lines = [];
        $this->getLinesRecursively($lines, $configs, $scope);
        $sfs->table(["code", "name"], $lines);

        return self::SUCCESS;
    }

    private function getLinesRecursively(&$lines, array $configs, ?string $scope)
    {
         foreach ($configs as $config) {
             if (is_array($config)) {
                 $this->getLinesRecursively($lines, $config, $scope);
             } else {
                 /** @var ConfigInterface $config */
                 $lines[] = [$config->getPath(), $config->getRawValue($scope)];
             }
         }
    }
}
