<?php

namespace App\Http\Controllers;

use App\Models\UserTransaction;

class InvoiceController extends Controller
{
    public function show($order_id)
    {
        // Ambil data transaksi berdasarkan order_id
        $transaction = UserTransaction::where('transaction_id', $order_id)->firstOrFail();

        // Mapping untuk payment method
        abstract class paymentMethods {
            public function getDisplayInfo() {
                return $this->getLabel() . ' | ' . $this->getInstruction();
            }
        
            abstract protected function getLabel();
            abstract protected function getInstruction();
        }
        
        class BankTransferMethod extends paymentMethods {
            protected function getLabel() {
                return 'Bank Transfer';
            }
        
            protected function getInstruction() {
                return 'Silakan transfer ke rekening berikut...';
            }
        }
        
        class QrisMethod extends paymentMethods {
            protected function getLabel() {
                return 'QRIS';
            }
        
            protected function getInstruction() {
                return 'Scan QR code berikut...';
            }
        }
        $method = new BankTransferMethod();
        echo $method->getDisplayInfo();
 
        

        // Cek apakah payment_method ada dalam mapping, jika tidak, set sebagai 'Unknown'
        $paymentMethodName = $paymentMethods[$transaction->payment_method] ?? 'Unknown Payment Method';

        // Tentukan tampilan berdasarkan status transaksi
        if ($transaction->status == 'success') {
            // Jika status transaksi 'success', tampilkan invoice sukses
            return view('page.checkout.invoice_success', ['transaction' => $transaction, 'paymentMethodName' => $paymentMethodName]);
        } elseif ($transaction->status == 'pending') {
            // Jika status transaksi 'pending', tampilkan invoice pending
            return view('page.checkout.invoice_pending', ['transaction' => $transaction, 'paymentMethodName' => $paymentMethodName]);
        } elseif ($transaction->status == 'failed') {
            // Jika status transaksi 'failed', tampilkan invoice failed
            return view('page.checkout.invoice_failed', ['transaction' => $transaction]);
        }

        // Jika status tidak terdeteksi, redirect atau tampilkan halaman error (opsional)
        return redirect()->route('cart.index');
    }
}