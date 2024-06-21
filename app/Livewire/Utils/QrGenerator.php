<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Contracts\View\View;
use JeroenDesloovere\VCard\VCard;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrGenerator extends Component
{
    public $activeTab = 'url';

    public $email;

    public $phone;

    public $name;

    public $company_name;

    public $address;

    public $websiteUrl;

    public $qrImage;

    public $qrCodeData;

    public $instagramLink;

    public $facebookLink;

    public $tiktokLink;

    public $whatsappLink;

    public $utmBuilder = false;

    public $utmSource;

    public $utmMedium;

    public $utmCampaign;

    public $utmTerm;

    public function render(): View
    {
        return view('livewire.utils.qr-generator');
    }

    public function refresh(): void
    {
        $this->reset();
    }

    public function generateWebsiteUrl(): void
    {
        if ($this->websiteUrl === null) {
            return;
        }

        // Remove existing UTM parameters from the website URL, if any
        $parsedUrl = parse_url((string) $this->websiteUrl);
        $this->websiteUrl = $parsedUrl['scheme'].'://'.$parsedUrl['host'].$parsedUrl['path'];

        $utmParams = [
            'utm_source'   => $this->utmSource,
            'utm_medium'   => $this->utmMedium,
            'utm_campaign' => $this->utmCampaign,
            'utm_term'     => $this->utmTerm,
        ];

        $query = http_build_query($utmParams);
        $this->websiteUrl .= '?'.$query;
    }

    public function data(): void
    {
        $this->name = 'Techno Service phone';
        $this->company_name = 'Techno Service phone';
        $this->phone = '+212696571641';
        $this->email = 'technoservicephone1@gmail.com';
        $this->address = '10 BOULEVARD ABDELMOUMEN MAGASIN n°8';
        $this->websiteUrl = 'www.technoservicephone.com';
        $this->instagramLink = 'https://www.instagram.com/technoservicephone/';
        $this->facebookLink = 'https://www.facebook.com/profile.php?id=100092743667081&mibextid=ZbWKwL';
        $this->tiktokLink = 'https://www.tiktok.com/@technoservicephone';
        $this->whatsappLink = '+212696571641';
    }

    public function generateQrCode($download = false): void
    {
        $vcard = new VCard();

        // Set the basic information
        $vcard->addName($this->name);
        $vcard->addCompany($this->company_name);
        $vcard->addPhoneNumber($this->phone);
        $vcard->addEmail($this->email);
        $vcard->addAddress($this->address);

        // Add social media links
        $vcard->addURL($this->websiteUrl, 'Website');
        $vcard->addURL($this->instagramLink, 'Instagram');
        $vcard->addURL($this->facebookLink, 'Facebook');
        $vcard->addURL($this->tiktokLink, 'TikTok');
        $vcard->addURL($this->whatsappLink, 'WhatsApp');

        $this->qrCodeData = $vcard->getOutput();
    }

    public function downloadQrCode()
    {
        $qrCode = QrCode::format('svg')
            ->style('square')
            ->size(400)
            ->eye('circle')
            ->generate($this->qrCodeData);

        // Set the appropriate response headers
        $headers = [
            'Content-Type'        => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr_code.svg"',
        ];

        // Return the response with the QR code image for download
        return response()->streamDownload(static function () use ($qrCode): void {
            echo $qrCode;
        }, 'qr_code.svg', $headers);
    }
}
