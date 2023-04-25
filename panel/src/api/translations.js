export default (api) => ({
	async list() {
		return api.get("translations");
	},
	async get(locale) {
		return api.get("translations/" + locale);
	}
});
