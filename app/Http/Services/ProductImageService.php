<?php

namespace App\Http\Services;

use App\Repositories\Interfaces\ProductImageRepositoryInterface;
use App\Helpers\FileStorage;
use Illuminate\Support\Str;

class ProductImageService
{
    private $productImageRepository;

    public function __construct(ProductImageRepositoryInterface $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;
    }

    public function getProductImages($productId)
    {
        return $this->productImageRepository->getAllProductImages($productId);
    }

    public function createProductImage($request)
    {
        $attributes = $request->only([
            'product_id',
            'variant_ids',
            'image_file',
            'position',
            'width',
            'height',
        ]);
        $attributes['src'] = FileStorage::storeImageFromUpload($attributes['image_file']);
        return $this->productImageRepository->storeImage($attributes);
    }

    public function createMultiProductImages($images, $productId)
    {
        $attributes = array_map(function ($image) use ($productId) {
            return [
                'id' => Str::uuid(),
                'product_id' => $productId,
                'src' => FileStorage::storeImageFromUpload($image),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $images);
        return $this->productImageRepository->insertImages($attributes);
    }

    public function updateMultiImagesOnProduct($imageAttrs, $productId)
    {
        foreach ($imageAttrs as $imageAttr) {
            if (isset($imageAttr['id'])) {
                $this->productImageRepository->updateImage($imageAttr['id'], $imageAttr);
            } else {
                $newImage = [
                    'id' => Str::uuid(),
                    'product_id' => $productId,
                    'position' => $imageAttr['position'] ?? null,
                    'width' => $imageAttr['width'] ?? null,
                    'height' => $imageAttr['height'] ?? null,
                    'src' => $imageAttr['src'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $this->productImageRepository->storeImage($newImage);
            }
        }
    }

    public function deleteAllImagesOnProduct($productId)
    {
        return $this->productImageRepository->deleteAllImagesOnProduct($productId);
    }
}
