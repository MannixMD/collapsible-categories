<?php
/**
 *
 * Collapsible Categories extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\collapsiblecategories\controller;

class controller implements main_interface
{
	/** @var \phpbb\collapsiblecategories\operator\operator */
	protected $operator;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	* Constructor
	*
	* @param \phpbb\collapsiblecategories\operator\operator     $operator           Collapsiblecategories Operator object
	* @param \phpbb\request\request                             $request            Request object
	* @access public
	*/
	public function __construct(\phpbb\collapsiblecategories\operator\operator $operator, \phpbb\request\request $request)
	{
		$this->operator = $operator;
		$this->request = $request;
	}

	/**
	 * This method processes AJAX requests for collapsible categories
	 * when a user collapses/expands a category. Collapsed categories
	 * will be stored to the user's db and cookie. Expanded categories
	 * will be removed from the user's db and cookie.
	 *
	 * @param int $forum_id A forum identifier
	 *
	 * @throws \phpbb\exception\http_exception An http exception
	 * @return \Symfony\Component\HttpFoundation\JsonResponse A Symfony JSON Response object
	 * @access public
	 */
	public function handle($forum_id)
	{
		// If this is no ajax request or the forum_id parameter is missing, we throw an exception. It is not supported.
		if (!$this->request->is_ajax() || $forum_id == 0)
		{
			throw new \phpbb\exception\http_exception(403, 'NO_AUTH_OPERATION');
		}

		// Update the user's collapsed category data for the given forum
		$response = $this->operator->set_user_categories($forum_id);

		// Return a JSON response
		return new \Symfony\Component\HttpFoundation\JsonResponse(array(
			'success' => $response,
		));
	}
}
