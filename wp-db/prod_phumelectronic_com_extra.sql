
--
-- Indexes for dumped tables
--

--
-- Indexes for table `ph0m31e_actionscheduler_actions`
--
ALTER TABLE `ph0m31e_actionscheduler_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `hook` (`hook`),
  ADD KEY `status` (`status`),
  ADD KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  ADD KEY `args` (`args`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `last_attempt_gmt` (`last_attempt_gmt`),
  ADD KEY `claim_id` (`claim_id`);

--
-- Indexes for table `ph0m31e_actionscheduler_claims`
--
ALTER TABLE `ph0m31e_actionscheduler_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `date_created_gmt` (`date_created_gmt`);

--
-- Indexes for table `ph0m31e_actionscheduler_groups`
--
ALTER TABLE `ph0m31e_actionscheduler_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `slug` (`slug`(191));

--
-- Indexes for table `ph0m31e_actionscheduler_logs`
--
ALTER TABLE `ph0m31e_actionscheduler_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `log_date_gmt` (`log_date_gmt`);

--
-- Indexes for table `ph0m31e_admin_columns`
--
ALTER TABLE `ph0m31e_admin_columns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `list_id` (`list_id`);

--
-- Indexes for table `ph0m31e_aepc_custom_audiences`
--
ALTER TABLE `ph0m31e_aepc_custom_audiences`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `ph0m31e_aepc_logs`
--
ALTER TABLE `ph0m31e_aepc_logs`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `ph0m31e_alm`
--
ALTER TABLE `ph0m31e_alm`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ph0m31e_alm_unlimited`
--
ALTER TABLE `ph0m31e_alm_unlimited`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ph0m31e_biz_plasgate_phonenumber`
--
ALTER TABLE `ph0m31e_biz_plasgate_phonenumber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_biz_plasgate_sms`
--
ALTER TABLE `ph0m31e_biz_plasgate_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_commentmeta`
--
ALTER TABLE `ph0m31e_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `ph0m31e_comments`
--
ALTER TABLE `ph0m31e_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10)),
  ADD KEY `woo_idx_comment_type` (`comment_type`);

--
-- Indexes for table `ph0m31e_layerslider`
--
ALTER TABLE `ph0m31e_layerslider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_layerslider_revisions`
--
ALTER TABLE `ph0m31e_layerslider_revisions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_links`
--
ALTER TABLE `ph0m31e_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indexes for table `ph0m31e_litespeed_url`
--
ALTER TABLE `ph0m31e_litespeed_url`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `url` (`url`(191)) USING BTREE,
  ADD KEY `cache_tags` (`cache_tags`(191)) USING BTREE;

--
-- Indexes for table `ph0m31e_litespeed_url_file`
--
ALTER TABLE `ph0m31e_litespeed_url_file`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `filename` (`filename`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `url_id_2` (`url_id`,`vary`,`type`) USING BTREE,
  ADD KEY `filename_2` (`filename`,`expired`) USING BTREE,
  ADD KEY `url_id` (`url_id`,`expired`) USING BTREE;

--
-- Indexes for table `ph0m31e_mailchimp_carts`
--
ALTER TABLE `ph0m31e_mailchimp_carts`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `ph0m31e_mailchimp_jobs`
--
ALTER TABLE `ph0m31e_mailchimp_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_options`
--
ALTER TABLE `ph0m31e_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Indexes for table `ph0m31e_pmxe_exports`
--
ALTER TABLE `ph0m31e_pmxe_exports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxe_google_cats`
--
ALTER TABLE `ph0m31e_pmxe_google_cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxe_posts`
--
ALTER TABLE `ph0m31e_pmxe_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxe_templates`
--
ALTER TABLE `ph0m31e_pmxe_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_files`
--
ALTER TABLE `ph0m31e_pmxi_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_hash`
--
ALTER TABLE `ph0m31e_pmxi_hash`
  ADD PRIMARY KEY (`hash`);

--
-- Indexes for table `ph0m31e_pmxi_history`
--
ALTER TABLE `ph0m31e_pmxi_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_images`
--
ALTER TABLE `ph0m31e_pmxi_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_imports`
--
ALTER TABLE `ph0m31e_pmxi_imports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_posts`
--
ALTER TABLE `ph0m31e_pmxi_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_pmxi_templates`
--
ALTER TABLE `ph0m31e_pmxi_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_postmeta`
--
ALTER TABLE `ph0m31e_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `ph0m31e_posts`
--
ALTER TABLE `ph0m31e_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- Indexes for table `ph0m31e_social_users`
--
ALTER TABLE `ph0m31e_social_users`
  ADD PRIMARY KEY (`social_users_id`),
  ADD KEY `ID` (`ID`,`type`),
  ADD KEY `identifier` (`identifier`);

--
-- Indexes for table `ph0m31e_termmeta`
--
ALTER TABLE `ph0m31e_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `ph0m31e_terms`
--
ALTER TABLE `ph0m31e_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indexes for table `ph0m31e_term_relationships`
--
ALTER TABLE `ph0m31e_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indexes for table `ph0m31e_term_taxonomy`
--
ALTER TABLE `ph0m31e_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indexes for table `ph0m31e_usermeta`
--
ALTER TABLE `ph0m31e_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `ph0m31e_users`
--
ALTER TABLE `ph0m31e_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `ph0m31e_wc_admin_notes`
--
ALTER TABLE `ph0m31e_wc_admin_notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `ph0m31e_wc_admin_note_actions`
--
ALTER TABLE `ph0m31e_wc_admin_note_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `ph0m31e_wc_category_lookup`
--
ALTER TABLE `ph0m31e_wc_category_lookup`
  ADD PRIMARY KEY (`category_tree_id`,`category_id`);

--
-- Indexes for table `ph0m31e_wc_customer_lookup`
--
ALTER TABLE `ph0m31e_wc_customer_lookup`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `ph0m31e_wc_download_log`
--
ALTER TABLE `ph0m31e_wc_download_log`
  ADD PRIMARY KEY (`download_log_id`),
  ADD KEY `permission_id` (`permission_id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `ph0m31e_wc_order_coupon_lookup`
--
ALTER TABLE `ph0m31e_wc_order_coupon_lookup`
  ADD PRIMARY KEY (`order_id`,`coupon_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `ph0m31e_wc_order_product_lookup`
--
ALTER TABLE `ph0m31e_wc_order_product_lookup`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `ph0m31e_wc_order_stats`
--
ALTER TABLE `ph0m31e_wc_order_stats`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `date_created` (`date_created`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status` (`status`(191));

--
-- Indexes for table `ph0m31e_wc_order_tax_lookup`
--
ALTER TABLE `ph0m31e_wc_order_tax_lookup`
  ADD PRIMARY KEY (`order_id`,`tax_rate_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `ph0m31e_wc_product_meta_lookup`
--
ALTER TABLE `ph0m31e_wc_product_meta_lookup`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `virtual` (`virtual`),
  ADD KEY `downloadable` (`downloadable`),
  ADD KEY `stock_status` (`stock_status`),
  ADD KEY `stock_quantity` (`stock_quantity`),
  ADD KEY `onsale` (`onsale`),
  ADD KEY `min_max_price` (`min_price`,`max_price`);

--
-- Indexes for table `ph0m31e_wc_reserved_stock`
--
ALTER TABLE `ph0m31e_wc_reserved_stock`
  ADD PRIMARY KEY (`order_id`,`product_id`);

--
-- Indexes for table `ph0m31e_wc_tax_rate_classes`
--
ALTER TABLE `ph0m31e_wc_tax_rate_classes`
  ADD PRIMARY KEY (`tax_rate_class_id`),
  ADD UNIQUE KEY `slug` (`slug`(191));

--
-- Indexes for table `ph0m31e_wc_webhooks`
--
ALTER TABLE `ph0m31e_wc_webhooks`
  ADD PRIMARY KEY (`webhook_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ph0m31e_woocommerce_api_keys`
--
ALTER TABLE `ph0m31e_woocommerce_api_keys`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `consumer_key` (`consumer_key`),
  ADD KEY `consumer_secret` (`consumer_secret`);

--
-- Indexes for table `ph0m31e_woocommerce_attribute_taxonomies`
--
ALTER TABLE `ph0m31e_woocommerce_attribute_taxonomies`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `attribute_name` (`attribute_name`(20));

--
-- Indexes for table `ph0m31e_woocommerce_downloadable_product_permissions`
--
ALTER TABLE `ph0m31e_woocommerce_downloadable_product_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  ADD KEY `download_order_product` (`download_id`,`order_id`,`product_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_order_remaining_expires` (`user_id`,`order_id`,`downloads_remaining`,`access_expires`);

--
-- Indexes for table `ph0m31e_woocommerce_log`
--
ALTER TABLE `ph0m31e_woocommerce_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `ph0m31e_woocommerce_order_itemmeta`
--
ALTER TABLE `ph0m31e_woocommerce_order_itemmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `meta_key` (`meta_key`(32));

--
-- Indexes for table `ph0m31e_woocommerce_order_items`
--
ALTER TABLE `ph0m31e_woocommerce_order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `ph0m31e_woocommerce_payment_tokenmeta`
--
ALTER TABLE `ph0m31e_woocommerce_payment_tokenmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `payment_token_id` (`payment_token_id`),
  ADD KEY `meta_key` (`meta_key`(32));

--
-- Indexes for table `ph0m31e_woocommerce_payment_tokens`
--
ALTER TABLE `ph0m31e_woocommerce_payment_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ph0m31e_woocommerce_sessions`
--
ALTER TABLE `ph0m31e_woocommerce_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_key` (`session_key`);

--
-- Indexes for table `ph0m31e_woocommerce_shipping_zones`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- Indexes for table `ph0m31e_woocommerce_shipping_zone_locations`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zone_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `location_type_code` (`location_type`(10),`location_code`(20));

--
-- Indexes for table `ph0m31e_woocommerce_shipping_zone_methods`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zone_methods`
  ADD PRIMARY KEY (`instance_id`);

--
-- Indexes for table `ph0m31e_woocommerce_tax_rates`
--
ALTER TABLE `ph0m31e_woocommerce_tax_rates`
  ADD PRIMARY KEY (`tax_rate_id`),
  ADD KEY `tax_rate_country` (`tax_rate_country`),
  ADD KEY `tax_rate_state` (`tax_rate_state`(2)),
  ADD KEY `tax_rate_class` (`tax_rate_class`(10)),
  ADD KEY `tax_rate_priority` (`tax_rate_priority`);

--
-- Indexes for table `ph0m31e_woocommerce_tax_rate_locations`
--
ALTER TABLE `ph0m31e_woocommerce_tax_rate_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `tax_rate_id` (`tax_rate_id`),
  ADD KEY `location_type_code` (`location_type`(10),`location_code`(20));

--
-- Indexes for table `ph0m31e_wpmailsmtp_tasks_meta`
--
ALTER TABLE `ph0m31e_wpmailsmtp_tasks_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ph0m31e_yith_wcwl`
--
ALTER TABLE `ph0m31e_yith_wcwl`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `prod_id` (`prod_id`);

--
-- Indexes for table `ph0m31e_yith_wcwl_lists`
--
ALTER TABLE `ph0m31e_yith_wcwl_lists`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `wishlist_token` (`wishlist_token`),
  ADD KEY `wishlist_slug` (`wishlist_slug`);

--
-- Indexes for table `ph0m31e_yoast_indexable`
--
ALTER TABLE `ph0m31e_yoast_indexable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_type_and_sub_type` (`object_type`,`object_sub_type`),
  ADD KEY `object_id_and_type` (`object_id`,`object_type`),
  ADD KEY `permalink_hash_and_object_type` (`permalink_hash`,`object_type`),
  ADD KEY `subpages` (`post_parent`,`object_type`,`post_status`,`object_id`),
  ADD KEY `prominent_words` (`prominent_words_version`,`object_type`,`object_sub_type`,`post_status`),
  ADD KEY `published_sitemap_index` (`object_published_at`,`is_robots_noindex`,`object_type`,`object_sub_type`);

--
-- Indexes for table `ph0m31e_yoast_indexable_hierarchy`
--
ALTER TABLE `ph0m31e_yoast_indexable_hierarchy`
  ADD PRIMARY KEY (`indexable_id`,`ancestor_id`),
  ADD KEY `indexable_id` (`indexable_id`),
  ADD KEY `ancestor_id` (`ancestor_id`),
  ADD KEY `depth` (`depth`);

--
-- Indexes for table `ph0m31e_yoast_migrations`
--
ALTER TABLE `ph0m31e_yoast_migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ph0m31e_yoast_migrations_version` (`version`);

--
-- Indexes for table `ph0m31e_yoast_primary_term`
--
ALTER TABLE `ph0m31e_yoast_primary_term`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_taxonomy` (`post_id`,`taxonomy`),
  ADD KEY `post_term` (`post_id`,`term_id`);

--
-- Indexes for table `ph0m31e_yoast_seo_links`
--
ALTER TABLE `ph0m31e_yoast_seo_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_direction` (`post_id`,`type`),
  ADD KEY `indexable_link_direction` (`indexable_id`,`type`);

--
-- Indexes for table `revo_access_key`
--
ALTER TABLE `revo_access_key`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_extend_products`
--
ALTER TABLE `revo_extend_products`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_flash_sale`
--
ALTER TABLE `revo_flash_sale`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_hit_products`
--
ALTER TABLE `revo_hit_products`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_list_categories`
--
ALTER TABLE `revo_list_categories`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_list_mini_banner`
--
ALTER TABLE `revo_list_mini_banner`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_mobile_slider`
--
ALTER TABLE `revo_mobile_slider`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_mobile_variable`
--
ALTER TABLE `revo_mobile_variable`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_notification`
--
ALTER TABLE `revo_notification`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_popular_categories`
--
ALTER TABLE `revo_popular_categories`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `revo_token_firebase`
--
ALTER TABLE `revo_token_firebase`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ph0m31e_actionscheduler_actions`
--
ALTER TABLE `ph0m31e_actionscheduler_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2765;

--
-- AUTO_INCREMENT for table `ph0m31e_actionscheduler_claims`
--
ALTER TABLE `ph0m31e_actionscheduler_claims`
  MODIFY `claim_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `ph0m31e_actionscheduler_groups`
--
ALTER TABLE `ph0m31e_actionscheduler_groups`
  MODIFY `group_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ph0m31e_actionscheduler_logs`
--
ALTER TABLE `ph0m31e_actionscheduler_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8273;

--
-- AUTO_INCREMENT for table `ph0m31e_admin_columns`
--
ALTER TABLE `ph0m31e_admin_columns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ph0m31e_aepc_custom_audiences`
--
ALTER TABLE `ph0m31e_aepc_custom_audiences`
  MODIFY `ID` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_aepc_logs`
--
ALTER TABLE `ph0m31e_aepc_logs`
  MODIFY `ID` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ph0m31e_alm`
--
ALTER TABLE `ph0m31e_alm`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ph0m31e_alm_unlimited`
--
ALTER TABLE `ph0m31e_alm_unlimited`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ph0m31e_biz_plasgate_phonenumber`
--
ALTER TABLE `ph0m31e_biz_plasgate_phonenumber`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `ph0m31e_biz_plasgate_sms`
--
ALTER TABLE `ph0m31e_biz_plasgate_sms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `ph0m31e_commentmeta`
--
ALTER TABLE `ph0m31e_commentmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ph0m31e_comments`
--
ALTER TABLE `ph0m31e_comments`
  MODIFY `comment_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=461;

--
-- AUTO_INCREMENT for table `ph0m31e_layerslider`
--
ALTER TABLE `ph0m31e_layerslider`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_layerslider_revisions`
--
ALTER TABLE `ph0m31e_layerslider_revisions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_links`
--
ALTER TABLE `ph0m31e_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_litespeed_url`
--
ALTER TABLE `ph0m31e_litespeed_url`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_litespeed_url_file`
--
ALTER TABLE `ph0m31e_litespeed_url_file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_mailchimp_jobs`
--
ALTER TABLE `ph0m31e_mailchimp_jobs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_options`
--
ALTER TABLE `ph0m31e_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=768064;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxe_exports`
--
ALTER TABLE `ph0m31e_pmxe_exports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxe_posts`
--
ALTER TABLE `ph0m31e_pmxe_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxe_templates`
--
ALTER TABLE `ph0m31e_pmxe_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_files`
--
ALTER TABLE `ph0m31e_pmxi_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_history`
--
ALTER TABLE `ph0m31e_pmxi_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_images`
--
ALTER TABLE `ph0m31e_pmxi_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_imports`
--
ALTER TABLE `ph0m31e_pmxi_imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_posts`
--
ALTER TABLE `ph0m31e_pmxi_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_pmxi_templates`
--
ALTER TABLE `ph0m31e_pmxi_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_postmeta`
--
ALTER TABLE `ph0m31e_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165631;

--
-- AUTO_INCREMENT for table `ph0m31e_posts`
--
ALTER TABLE `ph0m31e_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12676;

--
-- AUTO_INCREMENT for table `ph0m31e_social_users`
--
ALTER TABLE `ph0m31e_social_users`
  MODIFY `social_users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `ph0m31e_termmeta`
--
ALTER TABLE `ph0m31e_termmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71256;

--
-- AUTO_INCREMENT for table `ph0m31e_terms`
--
ALTER TABLE `ph0m31e_terms`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18808;

--
-- AUTO_INCREMENT for table `ph0m31e_term_taxonomy`
--
ALTER TABLE `ph0m31e_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18808;

--
-- AUTO_INCREMENT for table `ph0m31e_usermeta`
--
ALTER TABLE `ph0m31e_usermeta`
  MODIFY `umeta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13743;

--
-- AUTO_INCREMENT for table `ph0m31e_users`
--
ALTER TABLE `ph0m31e_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=441;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_admin_notes`
--
ALTER TABLE `ph0m31e_wc_admin_notes`
  MODIFY `note_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_admin_note_actions`
--
ALTER TABLE `ph0m31e_wc_admin_note_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51741;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_customer_lookup`
--
ALTER TABLE `ph0m31e_wc_customer_lookup`
  MODIFY `customer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_download_log`
--
ALTER TABLE `ph0m31e_wc_download_log`
  MODIFY `download_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_tax_rate_classes`
--
ALTER TABLE `ph0m31e_wc_tax_rate_classes`
  MODIFY `tax_rate_class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ph0m31e_wc_webhooks`
--
ALTER TABLE `ph0m31e_wc_webhooks`
  MODIFY `webhook_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_api_keys`
--
ALTER TABLE `ph0m31e_woocommerce_api_keys`
  MODIFY `key_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_attribute_taxonomies`
--
ALTER TABLE `ph0m31e_woocommerce_attribute_taxonomies`
  MODIFY `attribute_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_downloadable_product_permissions`
--
ALTER TABLE `ph0m31e_woocommerce_downloadable_product_permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_log`
--
ALTER TABLE `ph0m31e_woocommerce_log`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_order_itemmeta`
--
ALTER TABLE `ph0m31e_woocommerce_order_itemmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6662;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_order_items`
--
ALTER TABLE `ph0m31e_woocommerce_order_items`
  MODIFY `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=831;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_payment_tokenmeta`
--
ALTER TABLE `ph0m31e_woocommerce_payment_tokenmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_payment_tokens`
--
ALTER TABLE `ph0m31e_woocommerce_payment_tokens`
  MODIFY `token_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_sessions`
--
ALTER TABLE `ph0m31e_woocommerce_sessions`
  MODIFY `session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5380;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_shipping_zones`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zones`
  MODIFY `zone_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_shipping_zone_locations`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zone_locations`
  MODIFY `location_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_shipping_zone_methods`
--
ALTER TABLE `ph0m31e_woocommerce_shipping_zone_methods`
  MODIFY `instance_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_tax_rates`
--
ALTER TABLE `ph0m31e_woocommerce_tax_rates`
  MODIFY `tax_rate_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_woocommerce_tax_rate_locations`
--
ALTER TABLE `ph0m31e_woocommerce_tax_rate_locations`
  MODIFY `location_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_wpmailsmtp_tasks_meta`
--
ALTER TABLE `ph0m31e_wpmailsmtp_tasks_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `ph0m31e_yith_wcwl`
--
ALTER TABLE `ph0m31e_yith_wcwl`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `ph0m31e_yith_wcwl_lists`
--
ALTER TABLE `ph0m31e_yith_wcwl_lists`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `ph0m31e_yoast_indexable`
--
ALTER TABLE `ph0m31e_yoast_indexable`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `ph0m31e_yoast_migrations`
--
ALTER TABLE `ph0m31e_yoast_migrations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `ph0m31e_yoast_primary_term`
--
ALTER TABLE `ph0m31e_yoast_primary_term`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ph0m31e_yoast_seo_links`
--
ALTER TABLE `ph0m31e_yoast_seo_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `revo_access_key`
--
ALTER TABLE `revo_access_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `revo_extend_products`
--
ALTER TABLE `revo_extend_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `revo_flash_sale`
--
ALTER TABLE `revo_flash_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `revo_hit_products`
--
ALTER TABLE `revo_hit_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1098;

--
-- AUTO_INCREMENT for table `revo_list_categories`
--
ALTER TABLE `revo_list_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `revo_list_mini_banner`
--
ALTER TABLE `revo_list_mini_banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `revo_mobile_slider`
--
ALTER TABLE `revo_mobile_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `revo_mobile_variable`
--
ALTER TABLE `revo_mobile_variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `revo_notification`
--
ALTER TABLE `revo_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=647;

--
-- AUTO_INCREMENT for table `revo_popular_categories`
--
ALTER TABLE `revo_popular_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `revo_token_firebase`
--
ALTER TABLE `revo_token_firebase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ph0m31e_wc_download_log`
--
ALTER TABLE `ph0m31e_wc_download_log`
  ADD CONSTRAINT `fk_ph0m31e_wc_download_log_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `ph0m31e_woocommerce_downloadable_product_permissions` (`permission_id`) ON DELETE CASCADE;
