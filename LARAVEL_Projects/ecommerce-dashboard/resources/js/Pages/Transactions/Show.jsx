import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { formatDateTime } from '@/Utils/formatDate';

export default function TransactionsShow({ transaction }) {
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const getStatusBadge = (status) => {
        const badges = {
            'unpaid': 'bg-yellow-100 text-yellow-800',
            'paid': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800',
            'completed': 'bg-blue-100 text-blue-800'
        };
        return badges[status] || 'bg-gray-100 text-gray-800';
    };

    const handlePrint = () => {
        const printWindow = window.open('', '_blank');
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk - ${transaction.invoice_code}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: 'Courier New', monospace; padding: 20px; max-width: 300px; }
                    .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
                    .header h2 { font-size: 18px; margin-bottom: 5px; }
                    .info { margin-bottom: 10px; font-size: 12px; }
                    .info div { margin: 3px 0; }
                    .items { border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 10px 0; margin: 10px 0; }
                    .item { display: flex; justify-content: space-between; margin: 5px 0; font-size: 12px; }
                    .item-name { flex: 1; }
                    .item-qty { width: 60px; text-align: center; }
                    .item-price { width: 80px; text-align: right; }
                    .total { border-top: 2px dashed #000; padding-top: 10px; margin-top: 10px; }
                    .total-row { display: flex; justify-content: space-between; margin: 5px 0; font-size: 13px; }
                    .total-row.grand { font-weight: bold; font-size: 14px; margin-top: 10px; }
                    .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px dashed #000; font-size: 11px; }
                    @media print {
                        body { padding: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>ECO DASHBOARD</h2>
                    <div>Struk Pembelian</div>
                </div>
                
                    <div class="info">
                    <div><strong>Invoice:</strong> ${transaction.invoice_code}</div>
                    <div><strong>Tanggal:</strong> ${formatDateTime(transaction.created_at)}</div>
                    <div><strong>Kasir:</strong> ${transaction.user?.name || '-'}</div>
                    ${transaction.customer_name ? `<div><strong>Pelanggan:</strong> ${transaction.customer_name}</div>` : ''}
                </div>

                <div class="items">
                    ${transaction.details?.map(detail => `
                        <div class="item">
                            <div class="item-name">${detail.product?.name}</div>
                            <div class="item-qty">${detail.quantity}x</div>
                            <div class="item-price">${formatRupiah(detail.subtotal)}</div>
                        </div>
                    `).join('')}
                </div>

                <div class="total">
                    <div class="total-row grand">
                        <span>TOTAL</span>
                        <span>${formatRupiah(transaction.total_amount)}</span>
                    </div>
                    ${parseFloat(transaction.payment_amount) > 0 ? `
                        <div class="total-row">
                            <span>Dibayar</span>
                            <span>${formatRupiah(transaction.payment_amount)}</span>
                        </div>
                        <div class="total-row">
                            <span>Kembalian</span>
                            <span>${formatRupiah(transaction.change_amount)}</span>
                        </div>
                    ` : ''}
                </div>

                <div class="footer">
                    <div>Terima kasih atas kunjungan Anda!</div>
                    <div>Barang yang sudah dibeli tidak dapat dikembalikan</div>
                </div>

                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 100);
                    };
                </script>
            </body>
            </html>
        `;
        
        printWindow.document.write(printContent);
        printWindow.document.close();
    };

    return (
        <AppLayout title="Detail Transaksi">
            <div className="p-6">
                {/* Header */}
                <div className="mb-6 flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-800">Detail Transaksi</h2>
                        <p className="text-sm text-gray-600 mt-1">Informasi lengkap transaksi</p>
                    </div>
                    <Link
                        href="/transactions"
                        className="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </Link>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Transaction Info */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 className="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>
                            
                            <div className="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label className="text-sm text-gray-600">No. Invoice</label>
                                    <p className="font-semibold text-gray-900">{transaction.invoice_code}</p>
                                </div>
                                <div>
                                    <label className="text-sm text-gray-600">Status</label>
                                    <div>
                                        <span className={`inline-block px-3 py-1 text-xs font-semibold rounded-full ${getStatusBadge(transaction.status)}`}>
                                            {transaction.status === 'unpaid' && 'Belum Dibayar'}
                                            {transaction.status === 'paid' && 'Sudah Dibayar'}
                                            {transaction.status === 'cancelled' && 'Dibatalkan'}
                                            {transaction.status === 'completed' && 'Selesai'}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label className="text-sm text-gray-600">Tanggal Transaksi</label>
                                    <p className="font-semibold text-gray-900">{formatDateTime(transaction.created_at)}</p>
                                </div>
                                <div>
                                    <label className="text-sm text-gray-600">Kasir</label>
                                    <p className="font-semibold text-gray-900">{transaction.user?.name || '-'}</p>
                                </div>
                            </div>

                            {transaction.customer_name && (
                                <div className="border-t pt-4">
                                    <h4 className="text-sm font-semibold text-gray-700 mb-3">Informasi Pelanggan</h4>
                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className="text-sm text-gray-600">Nama</label>
                                            <p className="font-medium text-gray-900">{transaction.customer_name}</p>
                                        </div>
                                        <div>
                                            <label className="text-sm text-gray-600">Telepon</label>
                                            <p className="font-medium text-gray-900">{transaction.customer_phone || '-'}</p>
                                        </div>
                                        {transaction.customer_address && (
                                            <div className="col-span-2">
                                                <label className="text-sm text-gray-600">Alamat</label>
                                                <p className="font-medium text-gray-900">{transaction.customer_address}</p>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            )}

                            {/* Items */}
                            <div className="border-t mt-6 pt-6">
                                <h4 className="text-sm font-semibold text-gray-700 mb-3">Item Produk</h4>
                                <div className="space-y-3">
                                    {transaction.details?.map((detail, index) => (
                                        <div key={index} className="flex justify-between items-start p-3 bg-gray-50 rounded-lg">
                                            <div className="flex-1">
                                                <p className="font-medium text-gray-900">{detail.product?.name}</p>
                                                <p className="text-sm text-gray-600">
                                                    {formatRupiah(detail.price)} × {detail.quantity}
                                                </p>
                                            </div>
                                            <p className="font-semibold text-gray-900">
                                                {formatRupiah(detail.subtotal)}
                                            </p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Payment Summary */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                            <h3 className="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
                            
                            <div className="space-y-3">
                                <div className="flex justify-between text-gray-700">
                                    <span>Total</span>
                                    <span className="font-semibold">{formatRupiah(transaction.total_amount)}</span>
                                </div>
                                
                                {parseFloat(transaction.payment_amount) > 0 && (
                                    <>
                                        <div className="flex justify-between text-gray-700">
                                            <span>Dibayar</span>
                                            <span className="font-semibold">{formatRupiah(transaction.payment_amount)}</span>
                                        </div>
                                        <div className="flex justify-between text-gray-700">
                                            <span>Kembalian</span>
                                            <span className="font-semibold">{formatRupiah(transaction.change_amount)}</span>
                                        </div>
                                    </>
                                )}

                                <div className="border-t pt-3 mt-3">
                                    <div className="flex justify-between items-center">
                                        <span className="text-lg font-bold text-gray-900">Total Bayar</span>
                                        <span className="text-xl font-bold text-blue-600">
                                            {formatRupiah(transaction.total_amount)}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="mt-6 space-y-2">
                                <button 
                                    onClick={handlePrint}
                                    className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center"
                                >
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak Struk
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
