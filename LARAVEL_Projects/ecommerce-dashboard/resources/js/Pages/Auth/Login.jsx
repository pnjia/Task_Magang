import GuestLayout from '@/Layouts/GuestLayout';
import { useForm, Link } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">Login</h2>
                <p className="text-sm text-gray-600 mt-1">Silakan masuk ke akun Anda</p>
            </div>

            {status && (
                <div className="mb-4 font-medium text-sm text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit}>
                <div>
                    <label htmlFor="email" className="block font-medium text-sm text-gray-700">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && (
                        <div className="text-sm text-red-600 mt-2">{errors.email}</div>
                    )}
                </div>

                <div className="mt-4">
                    <label htmlFor="password" className="block font-medium text-sm text-gray-700">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    {errors.password && (
                        <div className="text-sm text-red-600 mt-2">{errors.password}</div>
                    )}
                </div>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        />
                        <span className="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div className="flex items-center justify-between mt-4">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Forgot your password?
                        </Link>
                    )}

                    <button
                        type="submit"
                        className="ms-auto inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        disabled={processing}>
                        Log in
                    </button>
                </div>

                <div className="text-center mt-4">
                    <Link
                        href={route('register')}
                        className="text-sm text-indigo-600 hover:text-indigo-900">
                        Belum punya akun? Daftar di sini
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
