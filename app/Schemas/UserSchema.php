<?php

namespace App\Schemas;

/**
 * @OA\Schema(
 *     schema="UserCreationRequest",
 *     type="object",
 *     required={"email", "password", "username", "first_name", "last_name", "phone_number", "status"},
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="user@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         example="password123"
 *     ),
 *     @OA\Property(
 *         property="username",
 *         type="string",
 *         example="johndoe"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         example="+123456789"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"on", "off"},
 *         example="on"
 *     )
 * )
 */
class UserSchema {}
