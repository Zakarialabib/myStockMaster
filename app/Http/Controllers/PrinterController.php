<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Printer;
use Exception;
use Illuminate\Http\Request;
use Log;

class PrinterController extends Controller
{
    public function index()
    {
        return view('admin.printer.index');
    }

    //  use livewire ==>

    public function create()
    {
        // access_printers

        $capability_profiles = Printer::capability_profiles();
        $connection_types = Printer::connection_types();

        return view('admin.printer.create')
            ->with(compact('capability_profiles', 'connection_types'));
    }

    public function store(Request $request)
    {
        // access_printers

        try {
            $input = $request->only(['name', 'connection_type', 'capability_profile', 'ip_address', 'port', 'path', 'char_per_line']);

            $input['created_by'] = $request->session()->get('user.id');

            if ($input['connection_type'] === 'network') {
                $input['path'] = '';
            } elseif (in_array($input['connection_type'], ['windows', 'linux'])) {
                $input['ip_address'] = '';
                $input['port'] = '';
            }

            $printer = new Printer();
            $printer->fill($input)->save();
            // this->alert
        } catch (Exception $e) {
            Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            // this->alert
        }

        return redirect('printers');
    }

    public function edit($id)
    {
        // access_printers

        $printer = Printer::find($id);

        $capability_profiles = Printer::capability_profiles();
        $connection_types = Printer::connection_types();

        return view('admin.printer.edit')
            ->with(compact('printer', 'capability_profiles', 'connection_types'));
    }

    public function update(Request $request, $id)
    {
        // access_printers

        try {
            $input = $request->only(['name', 'connection_type', 'capability_profile', 'ip_address', 'port', 'path', 'char_per_line']);

            $printer = Printer::findOrFail($id);

            if ($input['connection_type'] === 'network') {
                $input['path'] = '';
            } elseif (in_array($input['connection_type'], ['windows', 'linux'])) {
                $input['ip_address'] = '';
                $input['port'] = '';
            }

            $printer->fill($input)->save();

            // this->alert
        } catch (Exception $e) {
            Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            // this->alert
        }

        return redirect('printers');
    }

    public function destroy($id)
    {
        if ( ! auth()->user()->can('access_printers')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $printer = Printer::findOrFail($id);
                $printer->delete();

                // this->alert
            } catch (Exception $e) {
                Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                // this->alert
            }

            // return $output;
        }
    }
}
