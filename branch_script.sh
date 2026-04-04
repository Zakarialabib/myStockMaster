#!/bin/bash
set -e

# Make sure we are on master
git checkout master

# Branch 1: Search Product & Barcode (Task 1, 2, 6)
git checkout -b feature/ux-product-search
git checkout trae/solo-agent-ALMoNU -- \
  app/Livewire/Products/SearchProduct.php \
  resources/views/livewire/products/search-product.blade.php \
  resources/views/livewire/pos/index.blade.php \
  resources/views/livewire/products/barcode.blade.php \
  resources/views/admin/purchases/create.blade.php \
  resources/views/admin/quotation/quotation-sales/create.blade.php \
  resources/views/livewire/adjustment/create.blade.php \
  resources/views/livewire/adjustment/edit.blade.php
# Handle the deleted file (we need to rm it)
git rm resources/views/components/search-product.blade.php || true
git commit -m "feat(ux): extract search product to dedicated component with barcode scanner and skeleton loaders"

# Branch 2: Optimistic Cart (Task 3)
git checkout -b feature/ux-optimistic-cart
git checkout trae/solo-agent-ALMoNU -- \
  app/Livewire/Utils/ProductCart.php \
  resources/views/livewire/utils/product-cart.blade.php
git commit -m "feat(ux): implement optimistic alpine.js cart updates"

# Branch 3: Slideover Cart & Comboboxes (Task 4, 5)
git checkout -b feature/ux-slideover-combobox
git checkout trae/solo-agent-ALMoNU -- \
  resources/views/components/searchable-select.blade.php \
  resources/views/livewire/sales/create.blade.php \
  resources/views/livewire/sales/edit.blade.php \
  resources/views/livewire/purchase/create.blade.php \
  resources/views/livewire/purchase/edit.blade.php \
  resources/views/livewire/quotations/create.blade.php \
  resources/views/livewire/quotations/edit.blade.php \
  resources/views/livewire/sale-return/create.blade.php \
  resources/views/livewire/sale-return/edit.blade.php \
  resources/views/livewire/purchase-return/create.blade.php \
  resources/views/livewire/purchase-return/edit.blade.php
git commit -m "feat(ux): implement slide-over cart drawer and searchable comboboxes for clients/suppliers"

# Branch 4: Docs & Remaining Form Objects / Formatting (Task 7)
git checkout -b feature/ux-docs-formatting
git checkout trae/solo-agent-ALMoNU -- \
  docs/livewire.md \
  app/Livewire/Forms/PurchaseForm.php \
  app/Livewire/Forms/QuotationForm.php \
  app/Livewire/Forms/SaleForm.php \
  app/Livewire/Forms/SaleReturnForm.php \
  app/Livewire/Forms/UserForm.php \
  app/Livewire/Quotations/Create.php \
  app/Livewire/SaleReturn/Create.php
git commit -m "docs(ux): update livewire ux guidelines and apply form formatting"

# Push all branches
git push -u origin feature/ux-product-search
git push -u origin feature/ux-optimistic-cart
git push -u origin feature/ux-slideover-combobox
git push -u origin feature/ux-docs-formatting

