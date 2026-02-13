// Utility untuk memformat tanggal UTC dari database ke zona waktu WIB (Asia/Jakarta).
// Semua fungsi menerima string tanggal ISO 8601 dari Laravel
// (contoh: "2026-02-11T23:27:05.000000Z") dan mengembalikan string
// terformat dalam locale Indonesia (id-ID) dengan timezone Asia/Jakarta.

const TIMEZONE = 'Asia/Jakarta';
const LOCALE = 'id-ID';

export function formatDate(dateString) {
  if (!dateString) return '-';
  const d = new Date(dateString);
  if (isNaN(d.getTime())) return '-';

  return new Intl.DateTimeFormat(LOCALE, {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    timeZone: TIMEZONE,
  }).format(d);
}

export function formatDateTime(dateString) {
  if (!dateString) return '-';
  const d = normalizeToDate(dateString);
  if (!d || isNaN(d.getTime())) return '-';

  return new Intl.DateTimeFormat(LOCALE, {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: TIMEZONE,
  }).format(d);
}

export function formatTime(dateString) {
  if (!dateString) return '-';
  const d = normalizeToDate(dateString);
  if (!d || isNaN(d.getTime())) return '-';

  return new Intl.DateTimeFormat(LOCALE, {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: TIMEZONE,
  }).format(d);
}

function normalizeToDate(input) {
  if (!input) return null;
  if (input instanceof Date) return input;
  if (typeof input === 'number') return new Date(input);
  if (typeof input !== 'string') return null;

  const s = input.trim();
  const spaceDateTime = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
  if (spaceDateTime.test(s)) {
    return new Date(s.replace(' ', 'T') + 'Z');
  }

  const isoNoTZ = /^\d{4}-\d{2}-\d{2}T.*\d$/;
  if (isoNoTZ.test(s) && !s.endsWith('Z')) {
    return new Date(s + 'Z');
  }

  return new Date(s);
}

export function formatRelativeTime(dateString) {
  if (!dateString) return '-';
  const d = normalizeToDate(dateString);
  if (!d || isNaN(d.getTime())) return '-';

  // use Indonesian locale for human-friendly relative strings
  const rtf = new Intl.RelativeTimeFormat('id', { numeric: 'auto' });
  const now = Date.now();
  let diff = Math.round((d.getTime() - now) / 1000);

  const units = [
    { name: 'year', seconds: 31536000 },
    { name: 'month', seconds: 2592000 },
    { name: 'week', seconds: 604800 },
    { name: 'day', seconds: 86400 },
    { name: 'hour', seconds: 3600 },
    { name: 'minute', seconds: 60 },
    { name: 'second', seconds: 1 },
  ];

  const abs = Math.abs(diff);
  for (let i = 0; i < units.length; i++) {
    const u = units[i];
    if (abs >= u.seconds || u.name === 'second') {
      const value = Math.round(diff / u.seconds);
      return rtf.format(value, u.name);
    }
  }

  return '-';
}
