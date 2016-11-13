<?php

namespace Marking\Controllers;

use Marking\Models\Category;
use Marking\Models\Product;

class Browse extends Base
{

    /**
     * Gets the users details from SESSION and passes to the view
     */
    public function processBrowse()
    {
        $this->isNotLoggedIn();
        $products = new Product();
        $category = new Category();

        $viewData['first_name'] = $_SESSION['first_name'];
        $viewData['last_name'] = $_SESSION['last_name'];
        $viewData['category'] = $category->getAllCategories();
        $viewData['products'] = $products->getAllProducts();

        $this->render('Browse', 'browse.view', $viewData);
    }

}

