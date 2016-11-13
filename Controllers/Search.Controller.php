<?php


namespace Marking\Controllers;


use Marking\Models\Category;
use Marking\Models\Product;
use Marking\Models\User;

class Search extends Base
{

    /**
     * Gets the users details from SESSION and passes to the view
     */
    public function processSearch()
    {
        $this->isNotLoggedIn();
        $products = new Product();

        $viewData['first_name'] = $_SESSION['first_name'];
        $viewData['last_name'] = $_SESSION['last_name'];
        $viewData['products'] = $products->getAllProducts();

        $this->render('Search', 'search.view', $viewData);
    }

    /**
     * Ajax Search for products
     */
    public function ajaxSearchProducts()
    {
        $query = $_GET['query'];

        $products = new Product();

        $results = $products->ajaxSearchProducts($query);

        $this->renderAjax('ajax-results.view', $results);
    }

    /**
     * Ajax search for categories
     */
    public function ajaxSearchCategories()
    {
        $list = $_GET['query'];
        $list = explode(",", $list);

        /* If query is empty, return just the table header */
        if ($list[0] == '') {
            $this->renderAjax('ajax-results-empty.view', "");
            return;
        }

        $products = new Product();

        $results = $products->searchCategorys($list);

        $this->renderAjax('ajax-results.view', $results);
    }

    /**
     * Ajax Search for whether a username exists or not
     */
    public function usernameSearch()
    {
        $userName = $_GET['username'];

        $user = new User();

        $result = $user->isUsernameAvailable($userName);

        $this->renderAjax('ajax-check-username.view', $result);

    }

    /**
     * Ajax Search or whether an email address exists or not
     */
    public function emailSearch()
    {
        $userName = $_GET['email'];

        $user = new User();

        $result = $user->isEmailAvailable($userName);

        $this->renderAjax('ajax-check-email.view', $result);

    }


}

