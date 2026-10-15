<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$ids = $_POST['order_ids'] ?? [];
if (count($ids) < 2) { flash('error','Pilih minimal 2 pesanan untuk digabungkan.'); redirect(base_url('admin/merge.php')); }

$group = 'G-' . strtoupper(substr(uniqid(), -4));
$in = implode(',', array_fill(0, count($ids), '?'));
$pdo->prepare("UPDATE orders SET merge_group=? WHERE id IN ($in)")->execute(array_merge([$group], $ids));

flash('success', "Pesanan berhasil digabungkan dalam grup $group.");
redirect(base_url('admin/merge.php'));