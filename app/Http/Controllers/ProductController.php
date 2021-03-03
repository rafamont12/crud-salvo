<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class ProductController extends Controller
{
    /**
     * Return a listing of products.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return responder()->success(Product::paginate(1))->respond();
    }

    /**
     * Create a product in database.
     *
     * @param  ProductStoreRequest  $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request) : JsonResponse
    {
        $product = Product::create($request->validated());

        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * Return a concrete resource.
     *
     * @param  Product  $product
     * @return JsonResponse
     */
    public function show(Product $product) : JsonResponse
    {
        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * Update a product.
     *
     * @param  ProductUpdateRequest  $request
     * @param  Product $product
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product) : JsonResponse
    {
        $product->update($request->validated());

        return responder()->success($product, ProductTransformer::class)->respond();
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product) : JsonResponse
    {
        $product->delete();

        return responder()->success()->respond(204);
    }
}
