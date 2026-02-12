import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AppLayout({ title, children }) {
    const page = usePage();
    const { auth, pendingOrdersCount } = page.props;
    const component = page.component;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);

    // Helper to check if current page matches
    const isActive = (pageName) => {
        return component?.startsWith(pageName) || false;
    };

    // If no user data, show loading
    if (!auth || !auth.user) {
        return (
            <div className="flex items-center justify-center h-screen">
                <div className="text-center">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto"></div>
                    <p className="mt-4 text-gray-600">Loading...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="flex h-screen overflow-hidden">
            {/* Sidebar */}
            <aside 
                className={`flex-shrink-0 w-64 flex flex-col border-r border-gray-200 bg-white transition-all duration-300 ${
                    sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in md:translate-x-0 md:static fixed z-30 h-full'
                }`}>
                
                <div className="flex items-center justify-center h-16 bg-gray-900 text-white font-bold text-xl">
                    ECO DASHBOARD
                </div>

                <nav className="flex-1 overflow-y-auto py-4">
                    <ul className="space-y-1 px-2">
                        {auth.user.role === 'owner' && (
                            <li>
                                <Link 
                                    href="/dashboard" 
                                    className={`flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md ${
                                        isActive('Dashboard') ? 'bg-gray-100 font-semibold' : ''
                                    }`}>
                                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                        </path>
                                    </svg>
                                    Dashboard
                                </Link>
                            </li>
                        )}

                        {auth.user.role === 'owner' && (
                            <li>
                                <Link 
                                    href="/products" 
                                    className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                        isActive('Products/') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                    }`}>
                                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Produk
                                </Link>
                                <Link 
                                    href="/categories" 
                                    className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                        isActive('Categories/') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                    }`}>
                                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    Kategori
                                </Link>
                            </li>
                        )}

                        <li>
                            <Link 
                                href="/transactions/create" 
                                className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                    isActive('Transactions/Create') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                }`}>
                                <svg className="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 17v-3m-3 3h.01M13 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Kasir (POS)
                            </Link>
                        </li>

                        <li>
                            <Link 
                                href="/transactions" 
                                className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                    isActive('Transactions/Index') || isActive('Transactions/Show') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                }`}>
                                <svg className="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Pesanan Masuk
                                {pendingOrdersCount > 0 && (
                                    <span className="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                        {pendingOrdersCount > 99 ? '99+' : pendingOrdersCount}
                                    </span>
                                )}
                            </Link>
                        </li>

                        <li>
                            <Link 
                                href="/transactions/history" 
                                className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                    isActive('Transactions/History') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                }`}>
                                <svg className="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Riwayat Transaksi
                            </Link>
                        </li>

                        {auth?.user?.role === 'owner' && (
                            <li>
                                <Link 
                                    href="/users" 
                                    className={`flex items-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md ${
                                        isActive('Users/') ? 'bg-gray-100 font-semibold text-gray-900' : ''
                                    }`}>
                                    <svg className="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Staff / Users
                                </Link>
                            </li>
                        )}
                    </ul>
                </nav>
            </aside>

            {/* Main Content Area */}
            <div className="flex-1 flex flex-col overflow-hidden">
                {/* Header */}
                <header className="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200">
                    <button 
                        onClick={() => setSidebarOpen(!sidebarOpen)} 
                        className="text-gray-500 focus:outline-none md:hidden">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        {title || 'Dashboard'}
                    </h2>

                    <div className="flex items-center relative">
                        <button 
                            onClick={() => setDropdownOpen(!dropdownOpen)} 
                            className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{auth?.user?.name || 'User'}</div>
                            <div className="ms-1">
                                <svg className="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fillRule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clipRule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        {dropdownOpen && (
                            <div className="absolute right-0 top-full mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <Link 
                                    href="/profile" 
                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    onClick={() => setDropdownOpen(false)}>
                                    Profile
                                </Link>
                                <Link 
                                    href="/logout" 
                                    method="post" 
                                    as="button" 
                                    className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    onClick={() => setDropdownOpen(false)}>
                                    Log Out
                                </Link>
                            </div>
                        )}
                    </div>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    {children}
                </main>
            </div>

            {/* Overlay for mobile */}
            {sidebarOpen && (
                <div 
                    onClick={() => setSidebarOpen(false)}
                    className="fixed inset-0 z-20 bg-black opacity-50 transition-opacity md:hidden"></div>
            )}
        </div>
    );
}
