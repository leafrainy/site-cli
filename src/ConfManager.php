<?php
/**
 * SiteCLI - Help you manage Nginx local development configuration
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/site-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\SiteCli;

use Symfony\Component\Finder\Finder;

class ConfManager
{
    protected $available;

    protected $enabled;

    /**
     * @var \Panlatent\SiteCli\SiteGroup[]
     */
    protected $groups = [];

    public function __construct($available, $enabled)
    {
        $this->available = $available;
        $this->enabled = $enabled;
        $this->parser();
    }

    public function getGroup($name)
    {
        foreach ($this->groups as $groupName => $group) {
            if ($groupName == $name) {
                return $group;
            }
        }

        return false;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return \Panlatent\SiteCli\Site[]
     */
    public function getSites()
    {
        $sites = [];
        foreach ($this->groups as $group) {
            $sites = array_merge($sites, $group->getSites());
        }

        return $sites;
    }

    public function getServers()
    {

    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    protected function parser()
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in($this->available);
        foreach ($finder as $directory) {
            $name = $directory->getFilename();
            $this->groups[$name] = new SiteGroup($this, $name, $directory->getPathname());
        }
    }
}