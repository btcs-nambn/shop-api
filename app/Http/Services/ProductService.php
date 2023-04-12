<?php

namespace App\Http\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    private $productRepository;
    private $productOptionService;
    private $variantService;
    private $productImageService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductOptionService $productOptionService,
        VariantService $variantService,
        ProductImageService $productImageService
    ) {
        $this->productRepository = $productRepository;
        $this->productOptionService = $productOptionService;
        $this->variantService = $variantService;
        $this->productImageService = $productImageService;
    }

    public function createProduct($request)
    {
        $attributes = $request->only(['title', 'product_type', 'status', 'vendor']);
        $optionsAttr = $request->options;
        $variantsAttr = $request->variants;
        $imageAttr = $request->images;
        $product = DB::transaction(function () use ($attributes, $optionsAttr) {
            $product = $this->productRepository->createProduct($attributes);
            if ($optionsAttr) {
                $this->productOptionService->createOptions($optionsAttr, $product->id);
            }
            return $product;
        });
        if ($variantsAttr) {
            $this->variantService->createMultiVariant($variantsAttr, $product->id);
        }
        if ($imageAttr) {
            $this->productImageService->createMultiProductImages($imageAttr, $product->id);
        }
        return $product;
    }
}
