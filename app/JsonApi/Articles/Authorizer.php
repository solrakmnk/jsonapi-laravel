<?php

namespace App\JsonApi\Articles;

use CloudCreativity\LaravelJsonApi\Auth\AbstractAuthorizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class Authorizer extends AbstractAuthorizer
{
    protected $guards = ['sanctum'];

    /**
     * Authorize a resource index request.
     *
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function index($type, $request)
    {
        // TODO: Implement index() method.
    }

    /**
     * Authorize a resource create request.
     *
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function create($type, $request)
    {
        $this->authenticate();
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function read($record, $request)
    {
        // TODO: Implement read() method.
    }

    /**
     * Authorize a resource update request.
     *
     * @param $article
     * @param Request $request
     * @return void
     * @throws AuthorizationException if the request is not authorized.
     * @throws AuthenticationException
     */
    public function update($article, $request)
    {
        /*$this->authenticate();
        $this->authorize('update', $article);*/
        $this->can('update', $article);
    }

    /**
     * Authorize a resource read request.
     *
     * @param object $article
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @return void
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function delete($article, $request)
    {
        /*$this->authenticate();
        $this->authorize('delete', $article);*/
        $this->can('update', $article);

    }

}
