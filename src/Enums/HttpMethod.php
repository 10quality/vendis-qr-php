<?php
declare(strict_types=1);
namespace VendisQr\Enums;
/**
 * HTTP methods used by the official Vendis QR API.
 *
 * @version 1.0.0
 */
enum HttpMethod: string
{
    case Get = 'GET';
    case Post = 'POST';
}
