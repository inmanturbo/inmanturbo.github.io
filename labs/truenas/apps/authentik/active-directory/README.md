## Getting the `UPN` of an active directory user:

### In Authentik
- Customization>Property Mappings>Create Scope Mapping
  - name: `user_upn`
  - Scope name: `upn`
  - Expression:
  
  ```python
  return {
    "ak_proxy": {
        "user_attributes": {
            "additionalHeaders": {
                "x-authentik-upn": request.user.attributes.get('upn', None) if not request.user.email else request.user.email
            }
         }
     }
   }
   ```

- Add the scope to your provider
  - Providers>{{Provider}}>[Edit]>Advanced protocol settings
    - CTRL+CLICK to highlight `user_upn`

### In Truenas SCALE
- Apps>traefik>edit
  - Middlewares>forwardAuth>Add
  - authResponseHeaders>Add
    - `x-authentik-upn`

 
