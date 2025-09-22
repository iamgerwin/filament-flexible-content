# Packagist Setup Guide

## Publishing Your Package to Packagist

### Step 1: Submit Package to Packagist

1. Go to [https://packagist.org/packages/submit](https://packagist.org/packages/submit)
2. Enter your repository URL: `https://github.com/iamgerwin/filament-flexible-content`
3. Click "Check" and then "Submit"

### Step 2: Set Up Automatic Updates with GitHub Webhook

Once your package is accepted on Packagist, set up automatic updates:

#### Get Your Packagist API Token

1. Log in to [Packagist.org](https://packagist.org)
2. Go to your profile: [https://packagist.org/profile/](https://packagist.org/profile/)
3. Click on "Show API Token"
4. Copy your API token

#### Configure GitHub Webhook

1. Go to your GitHub repository: [https://github.com/iamgerwin/filament-flexible-content](https://github.com/iamgerwin/filament-flexible-content)
2. Navigate to **Settings** → **Webhooks**
3. Click **"Add webhook"**
4. Configure the webhook:

   **Payload URL:**
   ```
   https://packagist.org/api/github?username=YOUR_PACKAGIST_USERNAME
   ```
   Replace `YOUR_PACKAGIST_USERNAME` with your actual Packagist username.

   **Content type:**
   ```
   application/json
   ```

   **Secret:**
   ```
   YOUR_PACKAGIST_API_TOKEN
   ```
   Paste your Packagist API token here.

   **Which events would you like to trigger this webhook?**
   - Select: "Just the push event"

5. Click **"Add webhook"**

### Step 3: Verify Webhook

After setting up the webhook:

1. GitHub will send a test ping to Packagist
2. Check the webhook status in GitHub Settings → Webhooks
3. You should see a green checkmark if successful
4. The "Recent Deliveries" tab will show the ping event

### Step 4: Test Automatic Updates

1. Make a small change to your repository
2. Push to the main branch
3. Check Packagist - your package should update automatically within a few seconds
4. No more manual updates needed!

## Alternative: Using GitHub Service (Deprecated)

**Note:** The GitHub Service integration is deprecated. Use webhooks instead.

## Troubleshooting

### Webhook Not Working?

1. **Check the payload URL:** Ensure your username is correct
2. **Verify the secret:** Make sure you're using the API token, not your password
3. **Check Recent Deliveries:** Look for error messages in GitHub's webhook logs
4. **Regenerate API Token:** If needed, regenerate your token on Packagist and update the webhook

### Manual Update Fallback

If webhooks fail, you can always update manually:

1. Log in to Packagist
2. Go to your package page
3. Click "Update" button

## Benefits of Automatic Updates

- ✅ Instant updates when you push to GitHub
- ✅ No manual intervention needed
- ✅ Version tags automatically detected
- ✅ Composer users get updates immediately
- ✅ Works with all branches (configurable)

## Security Notes

- **Never commit your API token** to your repository
- **Keep your API token secret** - treat it like a password
- **Regenerate if compromised** - you can regenerate tokens anytime on Packagist

## Package Configuration

Your `composer.json` is already properly configured for Packagist:

```json
{
    "name": "iamgerwin/filament-flexible-content",
    "description": "Flexible Content & Repeater Fields for Laravel Filament v4",
    "type": "library",
    "license": "MIT",
    ...
}
```

## Version Management

Packagist automatically detects versions from:

1. **Git tags** (recommended):
   ```bash
   git tag v1.2.1
   git push origin v1.2.1
   ```

2. **Branch names** (for dev versions):
   - `main` branch → `dev-main`
   - `develop` branch → `dev-develop`

## Next Steps

After setting up the webhook:

1. Create git tags for releases:
   ```bash
   git tag v1.2.1
   git push origin v1.2.1
   ```

2. Users can now install your package:
   ```bash
   composer require iamgerwin/filament-flexible-content
   ```

3. Monitor your package statistics at:
   ```
   https://packagist.org/packages/iamgerwin/filament-flexible-content/stats
   ```