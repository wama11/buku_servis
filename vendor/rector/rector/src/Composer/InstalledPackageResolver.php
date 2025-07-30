<?php

declare (strict_types=1);
namespace Rector\Composer;

use RectorPrefix202503\Nette\Utils\FileSystem;
use RectorPrefix202503\Nette\Utils\Json;
use Rector\Composer\ValueObject\InstalledPackage;
use Rector\Exception\ShouldNotHappenException;
use RectorPrefix202503\Webmozart\Assert\Assert;
/**
 * @see \Rector\Tests\Composer\InstalledPackageResolverTest
 */
final class InstalledPackageResolver
{
    /**
     * @readonly
     */
    private ?string $projectDirectory = null;
    /**
     * @var InstalledPackage[]
     */
    private array $resolvedInstalledPackages = [];
    public function __construct(?string $projectDirectory = null)
    {
        $this->projectDirectory = $projectDirectory;
        // fallback to root project directory
        if ($projectDirectory === null) {
            $projectDirectory = \getcwd();
        }
        Assert::directory($projectDirectory);
    }
    /**
     * @return InstalledPackage[]
     */
    public function resolve() : array
    {
        // cache
        if ($this->resolvedInstalledPackages !== []) {
            return $this->resolvedInstalledPackages;
        }
        $installedPackagesFilePath = $this->projectDirectory . '/vendor/composer/installed.json';
        if (!\file_exists($installedPackagesFilePath)) {
            throw new ShouldNotHappenException('The installed package json not found. Make sure you run `composer update` and the "vendor/composer/installed.json" file exists');
        }
        $installedPackageFileContents = FileSystem::read($installedPackagesFilePath);
        $installedPackagesFilePath = Json::decode($installedPackageFileContents, \true);
        $installedPackages = $this->createInstalledPackages($installedPackagesFilePath['packages']);
        $this->resolvedInstalledPackages = $installedPackages;
        return $installedPackages;
    }
    /**
     * @param mixed[] $packages
     * @return InstalledPackage[]
     */
    private function createInstalledPackages(array $packages) : array
    {
        $installedPackages = [];
        foreach ($packages as $package) {
            $installedPackages[] = new InstalledPackage($package['name'], $package['version_normalized']);
        }
        return $installedPackages;
    }
}
