# Notification

A simple package that provides an OrderStatusUpdated event and a listener that sends a message to a Microsoft Teams webhook.

## Installation

This package is not an actual published package and is used for a technical exam.

Regardless, once this package is installed, Laravel will auto-detect this package and will register the provided Service Provider.

The Service Provider will bind by default a simple Mircosoft Teams `MessageCard` as the payload to your webhook.

If more control is wanted, disable auto-discovery for this package and register everything manually.

## Configuration

Set `notification.teams.webhook` (**required**) to your Microft Teams webhook URL.

Set `notification.teams.message_class` (Default: `SimpleTeamsMessagePayload`) to change how a message is sent to your webhook.

## Testing

```bash
./vendor/bin/testbench package:test tests
```
