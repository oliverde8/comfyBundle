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
use Symfony\Component\Validator\ConstraintViolationInterface;

class SetConfigsCommand extends Command
{
    public const ARG_PATH = "path";
    public const ARG_VALUE = "value";
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
        $this->setName("comfy:set");
        $this->setDescription("Set 'raw' configuration values for a given scope or default scope.");

        $this->addArgument(self::ARG_PATH, InputArgument::REQUIRED);
        $this->addArgument(self::ARG_VALUE);
        $this->addOption(self::OPT_SCOPE, null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument(self::ARG_PATH);
        $value = $input->getArgument(self::ARG_VALUE);
        $scope = $input->getOption(self::OPT_SCOPE) ?? null;

        $sfs = new SymfonyStyle($input, $output);
        /** @var ConfigInterface $config */
        $config = $this->configManager->getAllConfigs()->get($path);

        if (empty($config)) {
            $sfs->error("No config found for path '$path'");
            return self::SUCCESS;
        }

        $validations = $config->validate($value, $scope);
        if ($validations->count() > 0) {
            foreach ($validations as $violation) {
                /** @var $violation ConstraintViolationInterface */
                $sfs->warning($violation->getMessage());
            }
            return self::FAILURE;
        }

        $config->set($value, $scope);
        $sfs->success("Successfully saved new config value.");

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
