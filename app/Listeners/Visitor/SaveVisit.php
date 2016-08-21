<?php

namespace App\Listeners\Visitor;

use App\Events\Visitor\ViewedPage;
use App\Geolite\Location;
use App\Models\Domain;
use App\Models\Link;
use App\Models\Visit;
use App\Models\Visitor;
use App\Models\VisitorIp;
use App\Utilities\Url;

/**
 * When a user visits a website.
 *   Determine domain/s and save it.
 *   Save the links (visited & referer).
 *   Save the visit information (links ids).
 */
class SaveVisit
{
    /**
     * List of links that are stored into db.
     *
     * @var array
     */
    protected $links = [];

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var Visitor
     */
    protected $visitor;

    /**
     * Hold domain ids to not query the db twice.
     *
     * @var array
     */
    protected $domains = [];

    /**
     * Loop variable.
     *
     * @var mixed
     */
    protected $key;

    /**
     * Loop variable.
     *
     * @var array
     */
    protected $info = [];

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SaveVisitDetails $event
     */
    public function handle(ViewedPage $event)
    {
        $this->setEventData($event)
            ->thenSave();
    }

    /**
     * @param ViewedPage $event
     *
     * @return $this
     */
    protected function setEventData($event)
    {
        $this->links = [
            'visited' => new Url($event->request->server('HTTP_REFERER')),
            'referer' => new Url($event->request->get('referer')),
        ];

        $this->ip = $event->request->ip();

        $this->visitor = $event->visitor;

        return $this;
    }

    /**
     * @return $this
     */
    protected function thenSave()
    {
        foreach ($this->links as $key => $info) {
            $this->key = $key;
            $this->info = $info;

            if ($this->info->domain()) {
                $this->saveDomain();

                $this->saveLink();
                continue;
            }

            $this->nullifyLink();
        }

        $this->saveVisit();

        $this->saveIp();

        return $this;
    }

    /**
     * @return $this
     */
    protected function nullifyLink()
    {
        $this->links[$this->key] = null;

        return $this;
    }

    /**
     * Save domain on visit.
     * Then save it's id as reference.
     *
     * @return $this
     */
    protected function saveDomain()
    {
        $domain = $this->info->domain();
        if (isset($this->domains[$domain])) {
            return $this;
        }

        $domain = Domain::updateOrCreate(['name' => $domain]);

        $this->domains[$domain->name] = $domain->id;

        return $this;
    }

    /**
     * Save link and set it's id to $links.
     *
     * @return $this;
     */
    protected function saveLink()
    {
        $condition = [
            'domain_id' => $this->domains[$this->info->domain()],
            'path' => $this->info->path(),
            'is_secure' => $this->info->isSecure(),
            'is_www' => $this->info->isWWW(),
        ];

        $link = Link::firstOrCreate($condition, $this->info->toArray());

        $this->links[$this->key] = $link->id;

        return $this;
    }

    /**
     * Add visit details.
     *
     * @return $this
     */
    protected function saveVisit()
    {
        Visit::create([
            'visitor_id' => $this->visitor->id,
            'link_id' => $this->links['visited'],
            'referer_id' => $this->links['referer'],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function saveIp()
    {
        $geoname_id = null;
        if ($location = Location::byIp($this->ip)) {
            $geoname_id = $location->geoname_id;
        }

        VisitorIp::firstOrCreate([
            'ip' => $this->ip,
            'visitor_id' => $this->visitor->id,
            'geoname_id' => $geoname_id,
        ]);

        return $this;
    }
}
