<?
namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="Keranjang",
 *     type="object",
 *     title="Keranjang Schema",
 *     description="Schema untuk model Keranjang",
 *     @OA\Property(property="id", type="string", example="1"),
 *     @OA\Property(property="pasienId", type="string", example="1234567890"),
 *     @OA\Property(property="obatId", type="string", example="1"),
 *     @OA\Property(property="jumlah", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-21T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-21T10:00:00Z")
 * )
 */

class KeranjangSchema
{
}
