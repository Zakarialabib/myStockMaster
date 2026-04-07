# POS Audio Files

This directory contains audio feedback files for the Point of Sale (POS) system.

## Required Files

1. **beep.mp3** - Success sound for barcode scanning
   - Duration: ~100ms
   - Frequency: 800-1000 Hz
   - Volume: Low (30% playback volume)

2. **error.mp3** - Error sound for failed scans
   - Duration: ~200ms
   - Frequency: 200-300 Hz (buzz)
   - Volume: Low (30% playback volume)

## How to Add Audio Files

You can generate these sounds using:
- Online tone generators
- Audio editing software
- Free sound libraries

The system will work without these files (with console warnings), but for the best UX, please add them.
