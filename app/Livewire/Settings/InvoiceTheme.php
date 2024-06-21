<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

class InvoiceTheme extends Component
{
    public $templates = [];

    public $selectedTemplate = '';

    public $headerHtml;

    public $footerHtml;

    public function mount(): void
    {
        $this->templates = config('invoice.templates');
    }

    private function updateTemplatePreview(): void
    {
        // Load the selected template's header and footer HTML files
        $headerHtml = File::get(config('invoice.templates.'.$this->selectedTemplate.'.header_html'));
        $footerHtml = File::get(config('invoice.templates.'.$this->selectedTemplate.'.footer_html'));

        // Apply theme-specific changes to the header and footer HTML
        if ($this->selectedTheme === 'blue') {
            // Customize the header and footer for the "blue" theme
            $headerHtml = <<<HTML
                    <!-- Blue Theme Header HTML -->
                    <header style="background-color: #007bff; color: white;">
                        <!-- Customize header content for the "blue" theme -->
                        <h1>Blue Theme Header</h1>
                    </header>
                HTML;

            $footerHtml = <<<HTML
                    <!-- Blue Theme Footer HTML -->
                    <footer style="background-color: #007bff; color: white;">
                        <!-- Customize footer content for the "blue" theme -->
                        <p>Blue Theme Footer</p>
                    </footer>
                HTML;
        } elseif ($this->selectedTheme === 'orange') {
            // Customize the header and footer for the "orange" theme
            $headerHtml = <<<HTML
                    <!-- Orange Theme Header HTML -->
                    <header style="background-color: #ff6600; color: white;">
                        <!-- Customize header content for the "orange" theme -->
                        <h1>Orange Theme Header</h1>
                    </header>
                HTML;

            $footerHtml = <<<HTML
                    <!-- Orange Theme Footer HTML -->
                    <footer style="background-color: #ff6600; color: white;">
                        <!-- Customize footer content for the "orange" theme -->
                        <p>Orange Theme Footer</p>
                    </footer>
                HTML;
        }

        // Pass the updated HTML to the view
        $this->headerHtml = $headerHtml;
        $this->footerHtml = $footerHtml;
    }

    private function getCompanyLogo(): string
    {
        return 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/logo.png')));
    }

    private function setWaterMark($model)
    {
        return $model && $model->status ? $model->status : '';
    }

    public function sale($id): Response
    {
        // Load the selected template's header and footer HTML files
        $headerHtml = File::get(config('invoice.templates.'.$this->selectedTemplate.'.header_html'));
        $footerHtml = File::get(config('invoice.templates.'.$this->selectedTemplate.'.footer_html'));

        $sale = Sale::where('id', $id)->firstOrFail();
        $customer = Customer::where('id', $sale->customer->id)->firstOrFail();

        $data = [
            'sale'       => $sale,
            'customer'   => $customer,
            'logo'       => $this->getCompanyLogo(),
            'headerHtml' => $headerHtml,
            'footerHtml' => $footerHtml,
        ];

        $pdf = new Mpdf([
            'format'    => 'A4',
            'watermark' => $this->setWaterMark($sale),
        ]);

        $html = view('admin.sale.print', $data)->render();
        $pdf->WriteHTML($html);

        return response($pdf->Output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.__('Sale').$sale->reference.'.pdf"',
        ]);
    }

    protected function createHTMLfile($file, string $name): string
    {
        $extension = $file->extension();
        $data = File::get($file->getRealPath());
        $base64 = 'data:image/'.$extension.';base64,'.base64_encode($data);

        $html = sprintf(
            '<div><img style="width: 100%%; display: block;" src="%s"></div>',
            $base64
        );

        $path = public_path('print/'.$name.'.html');
        File::put($path, $html);

        return $base64;
    }

    public function render()
    {
        return view('livewire.settings.invoice-theme');
    }
}
