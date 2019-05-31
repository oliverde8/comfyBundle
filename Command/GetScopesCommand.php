<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle\Command;

use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetScopesCommand extends Command
{
    /** @var ScopeResolverInterface */
    protected $scopeResolver;

    /**
     * GetScopesCommand constructor.
     * @param ScopeResolverInterface $scopeResolver
     */
    public function __construct(ScopeResolverInterface $scopeResolver)
    {
        parent::__construct();

        $this->scopeResolver = $scopeResolver;
    }


    protected function configure(): void
    {
        parent::configure();
        $this->setName("comfy:scope:list");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sfs = new SymfonyStyle($input, $output);

        $lines = [];
        $this->getLinesRecursively($lines, $this->scopeResolver->getScopeTree());

        $sfs->table(["code", "name"], $lines);
    }

    private function getLinesRecursively(&$lines, $scopes, $depth = -1, $previousScopeCode = "")
    {
         foreach ($scopes as $scopeCode => $scopeName) {
             if ($scopeCode == "~name") {
                 $lines[] = [str_repeat("    ", $depth) . "- " . $previousScopeCode, $scopes["~name"]];
             } else {
                 $this->getLinesRecursively($lines, $scopeName, $depth + 1, $scopeCode);
             }
         }
    }
}